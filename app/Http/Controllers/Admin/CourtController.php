<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCourtRequest;
use App\Http\Requests\UpdateCourtRequest;
use App\Models\Court;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class CourtController extends Controller
{
    /**
     * Display all courts.
     */
    public function index(): Response
    {
        $courts = Court::withCount('bookings')->get();

        return Inertia::render('Admin/Courts/Index', [
            'courts' => $courts,
        ]);
    }

    /**
     * Show the form for creating a new court.
     */
    public function create(): Response
    {
        return Inertia::render('Admin/Courts/Create');
    }

    /**
     * Store a newly created court.
     */
    public function store(StoreCourtRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('courts', 'public');
        }

        Court::create($data);

        return redirect()
            ->route('admin.courts.index')
            ->with('success', 'Court created successfully.');
    }

    /**
     * Show the form for editing a court.
     */
    public function edit(Court $court): Response
    {
        return Inertia::render('Admin/Courts/Edit', [
            'court' => $court,
        ]);
    }

    /**
     * Update a court.
     */
    public function update(UpdateCourtRequest $request, Court $court): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($court->image) {
                Storage::disk('public')->delete($court->image);
            }
            $data['image'] = $request->file('image')->store('courts', 'public');
        }

        $court->update($data);

        return redirect()
            ->route('admin.courts.index')
            ->with('success', 'Court updated successfully.');
    }

    /**
     * Toggle court active status.
     */
    public function toggleActive(Court $court): RedirectResponse
    {
        $court->update(['is_active' => !$court->is_active]);

        $status = $court->is_active ? 'activated' : 'deactivated';

        return redirect()
            ->back()
            ->with('success', "Court {$status} successfully.");
    }
}
