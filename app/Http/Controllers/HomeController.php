<?php

namespace App\Http\Controllers;

use App\Models\Court;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    /**
     * Display the homepage.
     */
    public function index(): Response
    {
        $courts = Court::active()->get();

        return Inertia::render('Home', [
            'courts' => $courts,
        ]);
    }

    /**
     * Display the pricing page.
     */
    public function pricing(): Response
    {
        $courts = Court::active()->get();

        return Inertia::render('Pricing', [
            'courts' => $courts,
        ]);
    }

    /**
     * Display the contact page.
     */
    public function contact(): Response
    {
        return Inertia::render('Contact');
    }
}
