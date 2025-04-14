<?php

namespace Database\Seeders;

use App\Models\Inventory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 50; $i++) {
            Inventory::create([
                'item_name' => 'Item ' . ($i + 1),
                'category' => ['Electronics', 'Furniture', 'Office Supplies', 'Tools'][array_rand(['Electronics', 'Furniture', 'Office Supplies', 'Tools'])],
                'quantity' => rand(1, 100),
                'unit_price' => rand(10, 1000) / 10, // Generates prices like 10.5, 99.9, etc.
                'date_acquired' => Carbon::now()->subDays(rand(0, 365))->format('Y-m-d'),
                'supplier' => 'Supplier ' . Str::random(5),
                'location' => ['Warehouse A', 'Warehouse B', 'Storage Room', 'Main Office'][array_rand(['Warehouse A', 'Warehouse B', 'Storage Room', 'Main Office'])],
                'status' => ['in stock', 'out of stock', 'damaged'][array_rand(['in stock', 'out of stock', 'damaged'])],
            ]);
        }
    }
}
