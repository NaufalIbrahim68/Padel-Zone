<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CourtController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MyBookingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// === TEMPORARY: Database setup routes (remove after first deploy) ===
Route::get('/setup-database/{token}', function ($token) {
    if ($token !== 'padel-setup-2024-secret') {
        abort(403);
    }

    $output = [];

    try {
        // Run migrations
        Artisan::call('migrate', ['--force' => true]);
        $output[] = 'Migrations: ' . Artisan::output();

        // Run seeders
        Artisan::call('db:seed', ['--force' => true]);
        $output[] = 'Seeders: ' . Artisan::output();

        return '<pre>' . implode("\n", $output) . '</pre>';
    } catch (\Exception $e) {
        return '<pre>Error: ' . $e->getMessage() . "\n\nTrace:\n" . $e->getTraceAsString() . '</pre>';
    }
});
// === END TEMPORARY ===

// Public pages
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/courts', [CourtController::class, 'index'])->name('courts.index');
Route::get('/courts/{court}', [CourtController::class, 'show'])->name('courts.show');
Route::get('/pricing', [HomeController::class, 'pricing'])->name('pricing');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

// Authenticated user routes
Route::middleware('auth')->group(function () {
    // User dashboard
    Route::get('/dashboard', function () {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return \Inertia\Inertia::render('Dashboard');
    })->name('dashboard');

    // Profile (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Booking
    Route::redirect('/book', '/booking');
    Route::get('/booking', [BookingController::class, 'index'])->name('booking');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');

    // My Bookings
    Route::get('/my-bookings', [MyBookingController::class, 'index'])->name('my-bookings');
    Route::patch('/my-bookings/{booking}/cancel', [MyBookingController::class, 'cancel'])->name('my-bookings.cancel');

    // Availability API (uses web middleware for CSRF + auth)
    Route::get('/api/courts/{court}/availability', [AvailabilityController::class, 'show'])->name('availability');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Admin Bookings
    Route::get('/bookings', [Admin\BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [Admin\BookingController::class, 'show'])->name('bookings.show');
    Route::patch('/bookings/{booking}/status', [Admin\BookingController::class, 'updateStatus'])->name('bookings.update-status');

    // Admin Courts
    Route::get('/courts', [Admin\CourtController::class, 'index'])->name('courts.index');
    Route::get('/courts/create', [Admin\CourtController::class, 'create'])->name('courts.create');
    Route::post('/courts', [Admin\CourtController::class, 'store'])->name('courts.store');
    Route::get('/courts/{court}/edit', [Admin\CourtController::class, 'edit'])->name('courts.edit');
    Route::put('/courts/{court}', [Admin\CourtController::class, 'update'])->name('courts.update');
    Route::patch('/courts/{court}/toggle', [Admin\CourtController::class, 'toggleActive'])->name('courts.toggle');

    // Admin Calendar
    Route::get('/calendar', [Admin\CalendarController::class, 'index'])->name('calendar');
});

require __DIR__.'/auth.php';
