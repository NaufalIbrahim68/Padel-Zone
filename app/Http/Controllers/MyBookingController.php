<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MyBookingController extends Controller
{
    public function __construct(
        private BookingService $bookingService
    ) {}

    /**
     * Display the user's booking history.
     */
    public function index(Request $request): Response
    {
        $bookings = $request->user()
            ->bookings()
            ->with('court')
            ->orderByDesc('booking_date')
            ->orderByDesc('start_time')
            ->paginate(10);

        return Inertia::render('MyBookings', [
            'bookings' => $bookings,
        ]);
    }

    /**
     * Cancel a booking.
     */
    public function cancel(Booking $booking): RedirectResponse
    {
        $this->authorize('cancel', $booking);

        $this->bookingService->cancelBooking($booking);

        return redirect()
            ->route('my-bookings')
            ->with('success', 'Booking cancelled successfully.');
    }
}
