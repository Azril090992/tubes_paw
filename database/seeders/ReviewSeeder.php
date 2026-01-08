<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $user = \App\Models\User::where('email', 'user@cafe.com')->first();
        $cafe = \App\Models\Cafe::where('name', 'Cafe Latte')->first();

        if ($user && $cafe) {
            Review::firstOrCreate(
                ['user_id' => $user->id, 'cafe_id' => $cafe->id],
                [
                    'rating' => 5,
                    'comment' => 'Tempatnya nyaman, kopi enak banget!',
                ]
            );
        }
    }
}
