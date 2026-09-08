<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Court;
use App\Services\BookingService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class BookingController extends Controller
{
    public function __construct(
        private BookingService $bookingService
    ) {}

    /**
     * Display the booking page.
     */
    public function index(): Response
    {
        $courts = Court::active()->get();

        return Inertia::render('Booking/Index', [
            'courts' => $courts,
        ]);
    }

    /**
     * Store a new booking.
     */
    public function store(StoreBookingRequest $request): RedirectResponse
    {
        $booking = $this->bookingService->createBooking(
            $request->validated(),
            $request->user()
        );

        return redirect()
            ->route('bookings.show', $booking)
            ->with('success', 'Booking created successfully!');
    }

    /**
     * Display booking confirmation/details.
     */
    public function show(Booking $booking): Response
    {
        $this->authorize('view', $booking);

        $booking->load(['user', 'court']);

        return Inertia::render('Booking/Confirmation', [
            'booking' => $booking,
        ]);
    }
}
