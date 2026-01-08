<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Table;

class TableSeeder extends Seeder
{
    public function run(): void
    {
        $cafe = \App\Models\Cafe::where('name', 'Cafe Latte')->first();
        if (!$cafe)
            return;

        for ($i = 1; $i <= 10; $i++) {
            Table::firstOrCreate(
                ['name' => 'Table ' . $i, 'cafe_id' => $cafe->id],
                [
                    'capacity' => rand(2, 6),
                ]
            );
        }
    }
}
