<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateBookingRequest;
use App\Models\Booking;
use App\Models\Court;
use App\Services\BookingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BookingController extends Controller
{
    public function __construct(
        private BookingService $bookingService
    ) {}

    /**
     * Display all bookings with filtering and pagination.
     */
    public function index(Request $request): Response
    {
        $query = Booking::with(['user', 'court']);

        // Search by customer name or email
        if ($search = $request->input('search')) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by date
        if ($date = $request->input('date')) {
            $query->where('booking_date', $date);
        }

        // Filter by court
        if ($courtId = $request->input('court_id')) {
            $query->where('court_id', $courtId);
        }

        // Filter by status
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $bookings = $query->orderByDesc('booking_date')
            ->orderByDesc('start_time')
            ->paginate(15)
            ->withQueryString();

        $courts = Court::all();

        return Inertia::render('Admin/Bookings/Index', [
            'bookings' => $bookings,
            'courts' => $courts,
            'filters' => $request->only(['search', 'date', 'court_id', 'status']),
        ]);
    }

    /**
     * Display a single booking detail.
     */
    public function show(Booking $booking): Response
    {
        $booking->load(['user', 'court']);

        return Inertia::render('Admin/Bookings/Show', [
            'booking' => $booking,
        ]);
    }

    /**
     * Update a booking's status.
     */
    public function updateStatus(UpdateBookingRequest $request, Booking $booking): RedirectResponse
    {
        $this->bookingService->updateStatus($booking, $request->status);

        return redirect()
            ->back()
            ->with('success', 'Booking status updated successfully.');
    }
}
