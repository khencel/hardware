<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;

class SupervisorUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'firstname' => 'Test',
            'lastname' => 'Supervisor',
            'username' => 'supervisor',
            'password' => Hash::make('Pantukan@2025'),
            'is_active' => true
        ]);

        $user->roles()->create([
            'role_id' => 8
        ]);
    }
}
