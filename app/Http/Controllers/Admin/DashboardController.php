<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(
        private DashboardService $dashboardService
    ) {}

    /**
     * Display the admin dashboard.
     */
    public function index(Request $request): Response
    {
        $peakPeriod = in_array($request->query('peak_period'), ['7d', '30d', 'month', 'all'])
            ? $request->query('peak_period')
            : '7d';

        $trendPeriod = in_array($request->query('trend_period'), ['7d', '30d', 'month'])
            ? $request->query('trend_period')
            : '7d';

        $stats = $this->dashboardService->getStats();
        $peakBookingHours = $this->dashboardService->getPeakBookingHours($peakPeriod);
        $bookingTrend = $this->dashboardService->getBookingTrend($trendPeriod);

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
            'peakBookingHours' => $peakBookingHours,
            'bookingTrend' => $bookingTrend,
            'peakPeriod' => $peakPeriod,
            'trendPeriod' => $trendPeriod,
        ]);
    }
}
