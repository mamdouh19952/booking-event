<?php

namespace Database\Seeders;

use App\Models\Booking;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // Booking::factory()->count(10)->create();
        Booking::create([
            'user_id' => 1,
            'event_id' => 2,
            'status' => 'confirmed',
        ]);
         Booking::create([
            'user_id' => 2,
            'event_id' => 2,
            'status' => 'pending',
        ]);
          Booking::create([
            'user_id' => 3,
            'event_id' => 3,
            'status' => 'cancelled',
        ]);
    }
}
