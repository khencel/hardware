<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('discounts')->insert([
            [
                'title_name' => 'Summer Sale',
                'discount' => 10.00,
                'is_percentage' => true,
            ],
            [
                'title_name' => 'Holiday Discount',
                'discount' => 500.00,
                'is_percentage' => false,
            ],
            [
                'title_name' => 'Black Friday Deal',
                'discount' => 20.00,
                'is_percentage' => true,
            ],
            [
                'title_name' => 'New Year Special',
                'discount' => 15.00,
                'is_percentage' => true,
            ],
            [
                'title_name' => 'VIP Discount',
                'discount' => 1000.00,
                'is_percentage' => false,
            ],
        ]);
    }
}
