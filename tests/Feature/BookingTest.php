<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Court;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    private function createUser(array $attrs = []): User
    {
        return User::factory()->create(array_merge(['role' => 'user'], $attrs));
    }

    private function createAdmin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function createCourt(array $attrs = []): Court
    {
        return Court::create(array_merge([
            'name' => 'Test Court',
            'type' => 'Indoor',
            'price_per_hour' => 150000,
            'description' => 'Test court description',
            'is_active' => true,
        ], $attrs));
    }

    public function test_user_can_create_booking(): void
    {
        $user = $this->createUser();
        $court = $this->createCourt();

        $response = $this->actingAs($user)->post(route('bookings.store'), [
            'court_id' => $court->id,
            'booking_date' => now()->addDay()->format('Y-m-d'),
            'start_time' => '10:00',
            'end_time' => '11:00',
            'notes' => 'Test booking',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('bookings', [
            'user_id' => $user->id,
            'court_id' => $court->id,
            'status' => 'pending',
        ]);
    }

    public function test_guest_cannot_create_booking(): void
    {
        $court = $this->createCourt();

        $response = $this->post(route('bookings.store'), [
            'court_id' => $court->id,
            'booking_date' => now()->addDay()->format('Y-m-d'),
            'start_time' => '10:00',
            'end_time' => '11:00',
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_cannot_book_inactive_court(): void
    {
        $user = $this->createUser();
        $court = $this->createCourt(['is_active' => false]);

        $response = $this->actingAs($user)->post(route('bookings.store'), [
            'court_id' => $court->id,
            'booking_date' => now()->addDay()->format('Y-m-d'),
            'start_time' => '10:00',
            'end_time' => '11:00',
        ]);

        $response->assertSessionHasErrors('court_id');
    }

    public function test_cannot_book_already_booked_slot(): void
    {
        $user1 = $this->createUser();
        $user2 = $this->createUser(['email' => 'user2@test.com']);
        $court = $this->createCourt();
        $date = now()->addDay()->format('Y-m-d');

        // First booking should succeed
        Booking::create([
            'user_id' => $user1->id,
            'court_id' => $court->id,
            'booking_date' => $date,
            'start_time' => '10:00',
            'end_time' => '11:00',
            'total_price' => 150000,
            'status' => 'confirmed',
        ]);

        // Second booking for same slot should fail
        $response = $this->actingAs($user2)->post(route('bookings.store'), [
            'court_id' => $court->id,
            'booking_date' => $date,
            'start_time' => '10:00',
            'end_time' => '11:00',
        ]);

        $response->assertSessionHasErrors('start_time');
    }

    public function test_cancelled_booking_frees_slot(): void
    {
        $user1 = $this->createUser();
        $user2 = $this->createUser(['email' => 'user2@test.com']);
        $court = $this->createCourt();
        $date = now()->addDay()->format('Y-m-d');

        // Create and cancel a booking
        $booking = Booking::create([
            'user_id' => $user1->id,
            'court_id' => $court->id,
            'booking_date' => $date,
            'start_time' => '10:00',
            'end_time' => '11:00',
            'total_price' => 150000,
            'status' => 'cancelled',
        ]);

        // Same slot should be available for another user
        $response = $this->actingAs($user2)->post(route('bookings.store'), [
            'court_id' => $court->id,
            'booking_date' => $date,
            'start_time' => '10:00',
            'end_time' => '11:00',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('bookings', [
            'user_id' => $user2->id,
            'court_id' => $court->id,
            'booking_date' => $date,
            'start_time' => '10:00:00',
            'status' => 'pending',
        ]);
    }

    public function test_price_is_calculated_server_side(): void
    {
        $user = $this->createUser();
        $court = $this->createCourt(['price_per_hour' => 175000]);

        $this->actingAs($user)->post(route('bookings.store'), [
            'court_id' => $court->id,
            'booking_date' => now()->addDay()->format('Y-m-d'),
            'start_time' => '10:00',
            'end_time' => '11:00',
        ]);

        // Price should match court price, not anything from frontend
        $this->assertDatabaseHas('bookings', [
            'user_id' => $user->id,
            'total_price' => '175000.00',
        ]);
    }

    public function test_user_cannot_view_another_users_booking(): void
    {
        $user1 = $this->createUser();
        $user2 = $this->createUser(['email' => 'user2@test.com']);
        $court = $this->createCourt();

        $booking = Booking::create([
            'user_id' => $user1->id,
            'court_id' => $court->id,
            'booking_date' => now()->addDay()->format('Y-m-d'),
            'start_time' => '10:00',
            'end_time' => '11:00',
            'total_price' => 150000,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user2)->get(route('bookings.show', $booking));
        $response->assertForbidden();
    }

    public function test_user_can_view_own_booking(): void
    {
        $user = $this->createUser();
        $court = $this->createCourt();

        $booking = Booking::create([
            'user_id' => $user->id,
            'court_id' => $court->id,
            'booking_date' => now()->addDay()->format('Y-m-d'),
            'start_time' => '10:00',
            'end_time' => '11:00',
            'total_price' => 150000,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)->get(route('bookings.show', $booking));
        $response->assertOk();
    }

    public function test_user_can_cancel_own_pending_booking(): void
    {
        $user = $this->createUser();
        $court = $this->createCourt();

        $booking = Booking::create([
            'user_id' => $user->id,
            'court_id' => $court->id,
            'booking_date' => now()->addDay()->format('Y-m-d'),
            'start_time' => '10:00',
            'end_time' => '11:00',
            'total_price' => 150000,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)->patch(route('my-bookings.cancel', $booking));
        $response->assertRedirect();

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'cancelled',
        ]);
    }
}
