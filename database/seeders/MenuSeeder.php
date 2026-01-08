<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cafe = \App\Models\Cafe::where('name', 'Cafe Latte')->first();
        if (!$cafe)
            return;

        // Lookup categories dynamically
        $catCoffee = \App\Models\Category::where('name', 'Coffee')->first();
        $catNonCoffee = \App\Models\Category::where('name', 'Non-Coffee')->first();

        // Ensure categories exist
        if (!$catCoffee)
            $catCoffee = \App\Models\Category::create(['name' => 'Coffee']);
        if (!$catNonCoffee)
            $catNonCoffee = \App\Models\Category::create(['name' => 'Non-Coffee']);

        $menus = [
            [
                'cafe_id' => $cafe->id,
                'category_id' => $catCoffee->id,
                'name' => 'Cappuccino',
                'price' => 35000.00,
                'description' => 'Kopi yang dibuat dengan espresso dan susu.',
                'image' => 'https://source.unsplash.com/1600x900/?coffee, cappuccino',
                'is_available' => true,
            ],
            [
                'cafe_id' => $cafe->id,
                'category_id' => $catNonCoffee->id,
                'name' => 'Mocha',
                'price' => 40000.00,
                'description' => 'Kopi yang dibuat dengan coklat dan susu.',
                'image' => 'https://source.unsplash.com/1600x900/?coffee, mocha',
                'is_available' => true,
            ],
            [
                'cafe_id' => $cafe->id,
                'category_id' => $catNonCoffee->id,
                'name' => 'Latte',
                'price' => 45000.00,
                'description' => 'Kopi yang dibuat dengan susu dan kopi yang dikentalkan.',
                'image' => 'https://source.unsplash.com/1600x900/?coffee, latte',
                'is_available' => true,
            ],
        ];

        foreach ($menus as $menu) {
            Menu::firstOrCreate(
                ['name' => $menu['name'], 'cafe_id' => $menu['cafe_id']],
                $menu
            );
        }
    }
}
