<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Court;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BookingService
{
    /**
     * Operating hours.
     */
    const OPEN_HOUR = 6;
    const CLOSE_HOUR = 23;

    /**
     * Get available time slots for a court on a given date.
     */
    public function getAvailableSlots(Court $court, string $date): array
    {
        // Get all active bookings for this court on this date
        $bookedSlots = Booking::where('court_id', $court->id)
            ->where('booking_date', $date)
            ->active()
            ->pluck('start_time')
            ->map(fn($time) => substr($time, 0, 5))
            ->toArray();

        $slots = [];

        for ($hour = self::OPEN_HOUR; $hour < self::CLOSE_HOUR; $hour++) {
            $start = sprintf('%02d:00', $hour);
            $end = sprintf('%02d:00', $hour + 1);

            $slots[] = [
                'start' => $start,
                'end' => $end,
                'available' => !in_array($start, $bookedSlots),
            ];
        }

        return $slots;
    }

    /**
     * Create a booking with double-booking protection.
     *
     * Uses database transaction with pessimistic locking to prevent race conditions.
     *
     * @throws ValidationException
     */
    public function createBooking(array $data, User $user): Booking
    {
        return DB::transaction(function () use ($data, $user) {
            // Re-check availability inside transaction with a lock
            $existingBooking = Booking::where('court_id', $data['court_id'])
                ->where('booking_date', $data['booking_date'])
                ->where('start_time', $data['start_time'])
                ->active()
                ->lockForUpdate()
                ->first();

            if ($existingBooking) {
                throw ValidationException::withMessages([
                    'start_time' => 'The selected time slot is no longer available.',
                ]);
            }

            // Get court for price calculation
            $court = Court::findOrFail($data['court_id']);

            if (!$court->is_active) {
                throw ValidationException::withMessages([
                    'court_id' => 'This court is currently unavailable.',
                ]);
            }

            // Calculate price server-side — never trust frontend
            $totalPrice = $court->price_per_hour;

            return Booking::create([
                'user_id' => $user->id,
                'court_id' => $data['court_id'],
                'booking_date' => $data['booking_date'],
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
                'total_price' => $totalPrice,
                'status' => Booking::STATUS_PENDING,
                'notes' => $data['notes'] ?? null,
            ]);
        });
    }

    /**
     * Cancel a booking.
     *
     * @throws ValidationException
     */
    public function cancelBooking(Booking $booking): Booking
    {
        if (!$booking->canBeCancelled()) {
            throw ValidationException::withMessages([
                'status' => 'This booking cannot be cancelled.',
            ]);
        }

        $booking->update(['status' => Booking::STATUS_CANCELLED]);

        return $booking->fresh();
    }

    /**
     * Update booking status (admin action).
     *
     * @throws ValidationException
     */
    public function updateStatus(Booking $booking, string $status): Booking
    {
        $validTransitions = [
            Booking::STATUS_PENDING => [Booking::STATUS_CONFIRMED, Booking::STATUS_CANCELLED],
            Booking::STATUS_CONFIRMED => [Booking::STATUS_COMPLETED, Booking::STATUS_CANCELLED],
        ];

        $allowed = $validTransitions[$booking->status] ?? [];

        if (!in_array($status, $allowed)) {
            throw ValidationException::withMessages([
                'status' => "Cannot change status from '{$booking->status}' to '{$status}'.",
            ]);
        }

        $booking->update(['status' => $status]);

        return $booking->fresh();
    }
}
