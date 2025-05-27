<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Seeders\TaxSeeder;
use Illuminate\Database\Seeder;
use Database\Seeders\FoodSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\RoomSeeder;
use Database\Seeders\OptionSeeder;
use Database\Seeders\DiscountSeeder;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\InventorySeeder;
use Database\Seeders\FoodCategorySeeder;
use Database\Seeders\SupervisorUserSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            DiscountSeeder::class,
            FoodCategorySeeder::class,
            FoodSeeder::class,
            InventorySeeder::class,
            RoleSeeder::class,
            // RoomSeeder::class,
            SupervisorUserSeeder::class,
            TaxSeeder::class,
            OptionSeeder::class,
            // Add your other seeders here
        ]);
    }
}
