<?php

namespace Database\Seeders;

use App\Models\Court;
use Illuminate\Database\Seeder;

class CourtSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Court::create([
            'name' => 'Court 1',
            'type' => 'Indoor',
            'price_per_hour' => 150000,
            'description' => 'Premium indoor padel court with professional-grade glass walls, LED lighting system, and climate control. Perfect for competitive matches and training sessions.',
            'image' => 'courts/court-1.jpg',
            'is_active' => true,
        ]);

        Court::create([
            'name' => 'Court 2',
            'type' => 'Indoor',
            'price_per_hour' => 150000,
            'description' => 'Standard indoor padel court featuring high-quality turf, excellent visibility, and comfortable playing conditions year-round.',
            'image' => 'courts/court-2.jpg',
            'is_active' => true,
        ]);

        Court::create([
            'name' => 'Court 3',
            'type' => 'Outdoor',
            'price_per_hour' => 175000,
            'description' => 'Premium outdoor padel court with panoramic views, stadium lighting for evening play, and tournament-specification dimensions.',
            'image' => 'courts/court-3.jpg',
            'is_active' => true,
        ]);

        Court::create([
            'name' => 'Court 4',
            'type' => 'Outdoor',
            'price_per_hour' => 175000,
            'description' => 'Our flagship outdoor court with VIP seating area, professional lighting, and premium artificial turf surface.',
            'image' => 'courts/court-4.jpg',
            'is_active' => true,
        ]);
    }
}
