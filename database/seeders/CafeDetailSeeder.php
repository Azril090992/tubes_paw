<?php

namespace Database\Seeders;

use App\Models\CafeDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CafeDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cafe = \App\Models\Cafe::where('name', 'Cafe Latte')->first();
        if ($cafe) {
            CafeDetail::updateOrCreate(
                ['cafe_id' => $cafe->id],
                [
                    'description' => 'Cafe Latte adalah tempat yang sempurna untuk menikmati kopi berkualitas tinggi dan suasana yang nyaman.',
                    'wifi' => true,
                    'smoking_area' => false,
                    'power_plugs' => 10,
                    'photos' => json_encode(['https://example.com/photo1.jpg', 'https://example.com/photo2.jpg']),
                ]
            );
        }
    }
}
