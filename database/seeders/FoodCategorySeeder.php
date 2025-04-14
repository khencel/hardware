<?php

namespace Database\Seeders;

use App\Models\FoodCategory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FoodCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Fast Food',
            'Beverages',
            'Desserts',
            'Healthy Options',
            'Seafood',
            'Grilled',
            'Vegetarian'
        ];

        foreach ($categories as $category) {
            FoodCategory::create(['name' => $category]);
        }
    }
}
