<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Voucher;

class VoucherSeeder extends Seeder
{
    public function run(): void
    {
        Voucher::firstOrCreate(
            ['code' => 'FREECOFFEE'],
            [
                'title' => 'Free Coffee',
                'description' => 'Get a free coffee with this voucher.',
                'discount_amount' => 30000.00,
                'min_order' => 50000.00,
                'valid_from' => now(),
                'valid_until' => now()->addMonths(1),
                'status' => 'active',
            ]
        );

        Voucher::firstOrCreate(
            ['code' => 'HALFPRICE'],
            [
                'title' => 'Half Price',
                'description' => 'Get half price on your next order.',
                'discount_amount' => 0.50,
                'min_order' => 0,
                'valid_from' => now(),
                'valid_until' => now()->addMonths(1),
                'status' => 'active',
            ]
        );
    }
}
