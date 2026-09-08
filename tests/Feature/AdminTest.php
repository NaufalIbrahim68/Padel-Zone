<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_regular_user_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get(route('admin.dashboard'));
        $response->assertForbidden();
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        $response->assertOk();
    }

    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_regular_user_cannot_access_admin_bookings(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get(route('admin.bookings.index'));
        $response->assertForbidden();
    }

    public function test_admin_can_access_admin_bookings(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.bookings.index'));
        $response->assertOk();
    }

    public function test_regular_user_cannot_access_admin_courts(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get(route('admin.courts.index'));
        $response->assertForbidden();
    }

    public function test_admin_can_access_admin_courts(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.courts.index'));
        $response->assertOk();
    }

    public function test_admin_can_access_calendar(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.calendar'));
        $response->assertOk();
    }

    public function test_admin_dashboard_returns_chart_data(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $court = \App\Models\Court::create([
            'name' => 'Test Court',
            'type' => 'Indoor',
            'price_per_hour' => 150000,
            'description' => 'Test',
            'is_active' => true,
        ]);

        // Active booking today at 09:00
        \App\Models\Booking::create([
            'user_id' => $admin->id,
            'court_id' => $court->id,
            'booking_date' => now()->toDateString(),
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
            'total_price' => 150000,
            'status' => 'confirmed',
        ]);

        // Cancelled booking today at 09:00 (should not be counted)
        \App\Models\Booking::create([
            'user_id' => $admin->id,
            'court_id' => $court->id,
            'booking_date' => now()->toDateString(),
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
            'total_price' => 150000,
            'status' => 'cancelled',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        $response->assertOk();

        $response->assertInertia(fn (\Inertia\Testing\AssertableInertia $page) => $page
            ->component('Admin/Dashboard')
            ->has('peakBookingHours', 18) // 06:00 to 23:00 = 18 hours
            ->where('peakBookingHours.3.hour', '09:00') // 06:00(0), 07:00(1), 08:00(2), 09:00(3)
            ->where('peakBookingHours.3.bookings', 1)   // only 1 counted (cancelled excluded)
            ->has('bookingTrend', 7)                   // 7 days default
            ->where('trendPeriod', '7d')
            ->where('peakPeriod', '7d')
        );
    }

    public function test_admin_dashboard_supports_period_filtering(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.dashboard', [
            'trend_period' => '30d',
            'peak_period' => '30d',
        ]));
        $response->assertOk();

        $response->assertInertia(fn (\Inertia\Testing\AssertableInertia $page) => $page
            ->component('Admin/Dashboard')
            ->has('peakBookingHours', 18)
            ->has('bookingTrend', 30) // 30 days
            ->where('trendPeriod', '30d')
            ->where('peakPeriod', '30d')
        );
    }
}
