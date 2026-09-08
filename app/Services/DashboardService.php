<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Court;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * Get admin dashboard statistics.
     */
    public function getStats(): array
    {
        $today = Carbon::today();

        $todaysBookings = Booking::where('booking_date', $today)
            ->active()
            ->count();

        $todaysRevenue = Booking::where('booking_date', $today)
            ->active()
            ->sum('total_price');

        $totalCourts = Court::active()->count();

        // Courts that have no active booking right now
        $currentHour = Carbon::now()->format('H:00:00');
        $courtsBookedNow = Booking::where('booking_date', $today)
            ->where('start_time', '<=', $currentHour)
            ->where('end_time', '>', $currentHour)
            ->active()
            ->distinct('court_id')
            ->count('court_id');

        $availableCourts = $totalCourts - $courtsBookedNow;

        $upcomingBookings = Booking::where(function ($query) use ($today, $currentHour) {
            $query->where('booking_date', '>', $today)
                ->orWhere(function ($q) use ($today, $currentHour) {
                    $q->where('booking_date', $today)
                        ->where('start_time', '>', $currentHour);
                });
        })
            ->active()
            ->count();

        // Recent bookings for the dashboard list
        $recentBookings = Booking::with(['user', 'court'])
            ->latest()
            ->take(10)
            ->get();

        return [
            'todaysBookings' => $todaysBookings,
            'todaysRevenue' => $todaysRevenue,
            'availableCourts' => max(0, $availableCourts),
            'totalCourts' => $totalCourts,
            'upcomingBookings' => $upcomingBookings,
            'recentBookings' => $recentBookings,
        ];
    }

    /**
     * Get peak booking hours data (06:00 to 23:00).
     */
    public function getPeakBookingHours(string $period = '7d'): array
    {
        $today = Carbon::today();
        $startDate = null;
        $endDate = $today->copy();

        if ($period === '7d') {
            $startDate = $today->copy()->subDays(6);
        } elseif ($period === '30d') {
            $startDate = $today->copy()->subDays(29);
        } elseif ($period === 'month') {
            $startDate = $today->copy()->startOfMonth();
        }

        $query = Booking::where('status', '!=', Booking::STATUS_CANCELLED);

        if ($startDate && $endDate) {
            $query->whereBetween('booking_date', [$startDate->toDateString(), $endDate->toDateString()]);
        }

        $results = $query->select(
            DB::raw('HOUR(start_time) as booking_hour'),
            DB::raw('COUNT(*) as total')
        )
            ->groupBy('booking_hour')
            ->pluck('total', 'booking_hour')
            ->toArray();

        $hours = [];
        for ($h = 6; $h <= 23; $h++) {
            $hourKey = sprintf('%02d:00', $h);
            $hours[] = [
                'hour' => $hourKey,
                'hour_num' => $h,
                'bookings' => (int) ($results[$h] ?? 0),
            ];
        }

        return $hours;
    }

    /**
     * Get booking trend over a selected date period.
     */
    public function getBookingTrend(string $period = '7d'): array
    {
        $today = Carbon::today();
        $endDate = $today->copy();

        if ($period === '30d') {
            $startDate = $today->copy()->subDays(29);
        } elseif ($period === 'month') {
            $startDate = $today->copy()->startOfMonth();
        } else {
            // Default 7d
            $startDate = $today->copy()->subDays(6);
        }

        $results = Booking::where('status', '!=', Booking::STATUS_CANCELLED)
            ->whereBetween('booking_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->select(
                DB::raw('DATE(booking_date) as b_date'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('b_date')
            ->pluck('total', 'b_date')
            ->toArray();

        $trend = [];
        $current = $startDate->copy();
        while ($current <= $endDate) {
            $dateStr = $current->toDateString();
            $trend[] = [
                'date' => $dateStr,
                'label' => $current->format('M j'),
                'day' => $current->format('D'),
                'bookings' => (int) ($results[$dateStr] ?? 0),
            ];
            $current->addDay();
        }

        return $trend;
    }
}
