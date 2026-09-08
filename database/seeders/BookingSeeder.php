<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Court;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', 'user')->get();
        $courts = Court::all();
        $today = Carbon::today();

        // Generate bookings for today and upcoming days
        $bookings = [
            // Today's bookings
            ['user' => 0, 'court' => 0, 'date' => $today, 'hour' => 7, 'status' => 'confirmed'],
            ['user' => 1, 'court' => 0, 'date' => $today, 'hour' => 9, 'status' => 'confirmed'],
            ['user' => 2, 'court' => 0, 'date' => $today, 'hour' => 14, 'status' => 'pending'],
            ['user' => 0, 'court' => 1, 'date' => $today, 'hour' => 8, 'status' => 'confirmed'],
            ['user' => 1, 'court' => 1, 'date' => $today, 'hour' => 10, 'status' => 'confirmed'],
            ['user' => 2, 'court' => 2, 'date' => $today, 'hour' => 7, 'status' => 'confirmed'],
            ['user' => 0, 'court' => 2, 'date' => $today, 'hour' => 16, 'status' => 'pending'],
            ['user' => 1, 'court' => 3, 'date' => $today, 'hour' => 9, 'status' => 'confirmed'],
            ['user' => 2, 'court' => 3, 'date' => $today, 'hour' => 18, 'status' => 'pending'],

            // Tomorrow's bookings
            ['user' => 0, 'court' => 0, 'date' => $today->copy()->addDay(), 'hour' => 8, 'status' => 'confirmed'],
            ['user' => 1, 'court' => 0, 'date' => $today->copy()->addDay(), 'hour' => 10, 'status' => 'pending'],
            ['user' => 2, 'court' => 1, 'date' => $today->copy()->addDay(), 'hour' => 7, 'status' => 'confirmed'],
            ['user' => 0, 'court' => 1, 'date' => $today->copy()->addDay(), 'hour' => 15, 'status' => 'pending'],
            ['user' => 1, 'court' => 2, 'date' => $today->copy()->addDay(), 'hour' => 9, 'status' => 'confirmed'],
            ['user' => 2, 'court' => 3, 'date' => $today->copy()->addDay(), 'hour' => 11, 'status' => 'pending'],

            // Day after tomorrow
            ['user' => 0, 'court' => 0, 'date' => $today->copy()->addDays(2), 'hour' => 6, 'status' => 'pending'],
            ['user' => 1, 'court' => 1, 'date' => $today->copy()->addDays(2), 'hour' => 8, 'status' => 'pending'],
            ['user' => 2, 'court' => 2, 'date' => $today->copy()->addDays(2), 'hour' => 17, 'status' => 'pending'],
            ['user' => 0, 'court' => 3, 'date' => $today->copy()->addDays(2), 'hour' => 20, 'status' => 'pending'],

            // Past bookings (completed and cancelled for history)
            ['user' => 0, 'court' => 0, 'date' => $today->copy()->subDay(), 'hour' => 8, 'status' => 'completed'],
            ['user' => 1, 'court' => 1, 'date' => $today->copy()->subDay(), 'hour' => 10, 'status' => 'completed'],
            ['user' => 2, 'court' => 2, 'date' => $today->copy()->subDay(), 'hour' => 14, 'status' => 'completed'],
            ['user' => 0, 'court' => 3, 'date' => $today->copy()->subDay(), 'hour' => 16, 'status' => 'cancelled'],
            ['user' => 1, 'court' => 0, 'date' => $today->copy()->subDays(2), 'hour' => 7, 'status' => 'completed'],
            ['user' => 2, 'court' => 1, 'date' => $today->copy()->subDays(2), 'hour' => 9, 'status' => 'completed'],
        ];

        foreach ($bookings as $data) {
            $court = $courts[$data['court']];
            $startTime = sprintf('%02d:00', $data['hour']);
            $endTime = sprintf('%02d:00', $data['hour'] + 1);

            Booking::create([
                'user_id' => $users[$data['user']]->id,
                'court_id' => $court->id,
                'booking_date' => $data['date']->format('Y-m-d'),
                'start_time' => $startTime,
                'end_time' => $endTime,
                'total_price' => $court->price_per_hour,
                'status' => $data['status'],
                'notes' => null,
            ]);
        }
    }
}
