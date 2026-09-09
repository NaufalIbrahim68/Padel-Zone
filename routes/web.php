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
    $output[] = "PHP Version: " . PHP_VERSION;
    $output[] = "Loaded Extensions: " . implode(', ', array_filter(['pdo_mysql', 'openssl'], fn($e) => extension_loaded($e)));
    $output[] = "CA File /tmp/ca.pem exists: " . (file_exists('/tmp/ca.pem') ? 'YES (' . filesize('/tmp/ca.pem') . ' bytes)' : 'NO');
    $output[] = "CA File database/certs/ca.pem exists: " . (file_exists(base_path('database/certs/ca.pem')) ? 'YES' : 'NO');

    $host = env('DB_HOST');
    $port = env('DB_PORT', 4000);
    $user = env('DB_USERNAME');
    $pass = env('DB_PASSWORD');
    $dbName = env('DB_DATABASE', 'padel_booking');
    $caPath = file_exists('/tmp/ca.pem') ? '/tmp/ca.pem' : base_path('database/certs/ca.pem');

    // Step 1: Ensure database exists
    try {
        $pdoOptions = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ];
        if (file_exists($caPath)) {
            $pdoOptions[PDO::MYSQL_ATTR_SSL_CA] = $caPath;
        }
        if (defined('PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT')) {
            $pdoOptions[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
        }

        $pdo = new PDO("mysql:host={$host};port={$port}", $user, $pass, $pdoOptions);
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}`");
        $output[] = "Step 1: Database `{$dbName}` verified/created successfully!";
    } catch (\Exception $e) {
        $output[] = "Step 1 (Create DB) Warning: " . $e->getMessage();
    }

    // Step 2: Run migrations
    try {
        Artisan::call('migrate', ['--force' => true]);
        $output[] = "Step 2 (Migrations):\n" . Artisan::output();
    } catch (\Exception $e) {
        $output[] = "Step 2 (Migrations) Error: " . $e->getMessage();
    }

    // Step 3: Run seeders
    try {
        Artisan::call('db:seed', ['--force' => true]);
        $output[] = "Step 3 (Seeders):\n" . Artisan::output();
    } catch (\Exception $e) {
        $output[] = "Step 3 (Seeders) Error: " . $e->getMessage();
    }

    return '<pre>' . implode("\n\n", $output) . '</pre>';
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
