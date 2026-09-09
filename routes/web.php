<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CourtController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MyBookingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public pages
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/courts', [CourtController::class, 'index'])->name('courts.index');
Route::get('/courts/{court}', [CourtController::class, 'show'])->name('courts.show');
Route::get('/pricing', [HomeController::class, 'pricing'])->name('pricing');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

// Availability API (publicly accessible so users can select slots anytime)
Route::get('/api/courts/{court}/availability', [AvailabilityController::class, 'show'])->name('availability');

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
