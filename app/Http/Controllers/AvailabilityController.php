<?php

namespace App\Http\Controllers;

use App\Models\Court;
use App\Services\BookingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    public function __construct(
        private BookingService $bookingService
    ) {}

    /**
     * Get available time slots for a court on a given date.
     */
    public function show(Court $court, Request $request): JsonResponse
    {
        $request->validate([
            'date' => ['required', 'date', 'after_or_equal:today'],
        ]);

        $slots = $this->bookingService->getAvailableSlots($court, $request->date);

        return response()->json([
            'date' => $request->date,
            'court' => [
                'id' => $court->id,
                'name' => $court->name,
            ],
            'slots' => $slots,
        ]);
    }
}
