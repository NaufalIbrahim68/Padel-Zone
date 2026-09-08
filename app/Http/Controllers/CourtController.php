<?php

namespace App\Http\Controllers;

use App\Models\Court;
use Inertia\Inertia;
use Inertia\Response;

class CourtController extends Controller
{
    /**
     * Display courts listing.
     */
    public function index(): Response
    {
        $courts = Court::active()->get();

        return Inertia::render('Courts/Index', [
            'courts' => $courts,
        ]);
    }

    /**
     * Display a single court.
     */
    public function show(Court $court): Response
    {
        return Inertia::render('Courts/Show', [
            'court' => $court,
        ]);
    }
}
