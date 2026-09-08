<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Court;
use App\Services\BookingService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CalendarController extends Controller
{
    public function __construct(
        private BookingService $bookingService
    ) {}

    /**
     * Display the admin calendar view.
     */
    public function index(Request $request): Response
    {
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));

        $courts = Court::active()->get();

        // Get all bookings for this date
        $bookings = Booking::with(['user', 'court'])
            ->where('booking_date', $date)
            ->active()
            ->get();

        // Build the schedule grid
        $schedule = [];
        for ($hour = BookingService::OPEN_HOUR; $hour < BookingService::CLOSE_HOUR; $hour++) {
            $timeSlot = sprintf('%02d:00', $hour);
            $endSlot = sprintf('%02d:00', $hour + 1);
            $row = [
                'time' => $timeSlot,
                'end' => $endSlot,
                'courts' => [],
            ];

            foreach ($courts as $court) {
                $booking = $bookings->first(function ($b) use ($court, $timeSlot) {
                    return $b->court_id === $court->id
                        && substr($b->start_time, 0, 5) === $timeSlot;
                });

                $row['courts'][] = [
                    'court_id' => $court->id,
                    'court_name' => $court->name,
                    'available' => !$booking,
                    'booking' => $booking ? [
                        'id' => $booking->id,
                        'user_name' => $booking->user->name,
                        'status' => $booking->status,
                    ] : null,
                ];
            }

            $schedule[] = $row;
        }

        return Inertia::render('Admin/Calendar', [
            'date' => $date,
            'courts' => $courts,
            'schedule' => $schedule,
        ]);
    }
}
