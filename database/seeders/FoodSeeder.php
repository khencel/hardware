<?php

namespace Database\Seeders;

use App\Models\Food;
use App\Models\FoodCategory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FoodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $foods = [
            ['name' => 'Cheeseburger', 'category' => 'Fast Food', 'price' => 150.00],
            ['name' => 'French Fries', 'category' => 'Fast Food', 'price' => 80.00],
            ['name' => 'Coca Cola', 'category' => 'Beverages', 'price' => 50.00],
            ['name' => 'Green Salad', 'category' => 'Healthy Options', 'price' => 120.00],
            ['name' => 'Grilled Salmon', 'category' => 'Seafood', 'price' => 250.00],
            ['name' => 'Chocolate Cake', 'category' => 'Desserts', 'price' => 180.00]
        ];

        foreach ($foods as $food) {
            $category = FoodCategory::where('name', $food['category'])->first();
            if ($category) {
                Food::create([
                    'name' => $food['name'],
                    'category_id' => $category->id,
                    'price' => $food['price'],
                    'is_available' => true,
                ]);
            }
        }
    }
}
