<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $user = \App\Models\User::where('email', 'user@cafe.com')->first();
        $cafe = \App\Models\Cafe::where('name', 'Cafe Latte')->first();

        if ($user && $cafe) {
            $table = \App\Models\Table::where('cafe_id', $cafe->id)->first();

            if ($table) {
                Booking::firstOrCreate(
                    ['booking_code' => 'BOOK123456'],
                    [
                        'user_id' => $user->id,
                        'cafe_id' => $cafe->id,
                        'table_id' => $table->id,
                        'booking_time' => now()->addDay(),
                        'status' => 'completed',
                        'total_amount' => 50000,
                        'people_count' => 2,
                        'arrival_time' => now()->addDay()->addHour(),
                        'final_amount' => 45000,
                        'voucher_amount' => 5000,
                    ]
                );
            }
        }
    }
}
