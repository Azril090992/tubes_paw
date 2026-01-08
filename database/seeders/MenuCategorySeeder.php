<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class MenuCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Coffee', 'Non-Coffee', 'Snacks', 'Main Course'];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category],
                []
            );
        }
    }
}
