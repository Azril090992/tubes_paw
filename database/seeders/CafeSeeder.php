<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cafe;

class CafeSeeder extends Seeder
{
    public function run(): void
    {
        $owner = \App\Models\User::where('email', 'latte@cafe.com')->first();

        if ($owner) {
            Cafe::firstOrCreate(
                ['name' => 'Cafe Latte'],
                [
                    'owner_id' => $owner->id,
                    'address' => 'Jl. Merdeka No. 10, Jakarta',
                    'distance' => 1.5, // Replaces lat/long
                    'open_time' => '08:00:00',
                    'close_time' => '22:00:00',
                    'status' => 'active',
                ]
            );
        }
    }
}
