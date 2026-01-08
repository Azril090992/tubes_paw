<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CafeSeeder::class,
            CafeDetailSeeder::class,
            MenuCategorySeeder::class,
            MenuSeeder::class,
            TableSeeder::class,
            VoucherSeeder::class,
            BookingSeeder::class,
            ReviewSeeder::class,
        ]);
    }
}
