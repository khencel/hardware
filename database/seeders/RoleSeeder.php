<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->truncate();
        $roles = [
            ['name' => 'Reservationist'],
            ['name' => 'Front Desk'],
            ['name' => 'Inventory Manager'],
            ['name' => 'Cashier'],
            ['name' => 'Server/Waiter'],
            ['name' => 'Food Server'],
            ['name' => 'Restaurant Inventory Manager'],
            ['name' => 'Supervisor'],
            ['name' => 'Supervisor Aide'],
            ['name' => 'Guest Counter'],
            ['name' => 'Errand'],
            ['name' => 'Super Admin'],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(['name' => $role['name']], $role);
        }
    }
}
