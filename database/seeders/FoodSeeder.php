<?php

namespace Database\Seeders;

use App\Models\Food;
use App\Models\FoodCategory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Faker\Factory as Faker;

class FoodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $foods = [
            ['name' => 'Cheeseburger', 'category' => 'Fast Food','cost_price' => 100.00,'selling_price' => 150.00],
            ['name' => 'French Fries', 'category' => 'Fast Food', 'cost_price' => 60.00, 'selling_price' => 80.00],
            ['name' => 'Coca Cola', 'category' => 'Beverages', 'cost_price' => 30.00, 'selling_price' => 50.00],
            ['name' => 'Green Salad', 'category' => 'Healthy Options', 'cost_price' => 100.00, 'selling_price' => 120.00],
            ['name' => 'Grilled Salmon', 'category' => 'Seafood', 'cost_price' => 200.00 ,'selling_price' => 250.00],
            ['name' => 'Chocolate Cake', 'category' => 'Desserts', 'cost_price' => 150.00, 'selling_price' => 180.00]
        ];

        foreach ($foods as $food) {
            $category = FoodCategory::where('name', $food['category'])->first();
            if ($category) {
                Food::create([
                    'name' => $food['name'],
                    'category_id' => $category->id,
                    'price' => $food['selling_price'],
                    'margin_percentage' => $food['selling_price'] > 0 
                    ? round((($food['selling_price'] - $food['cost_price']) / $food['selling_price']) * 100, 2) 
                    : 0,
                    'cost_price' => $food['cost_price'],
                    'is_available' => true,
                    'barcode' => $faker->unique()->numerify('480###########'),
                    'quantity' => rand(1, 100) // Random quantity between 1 and 100
                ]);
            }
        }
    }
}
