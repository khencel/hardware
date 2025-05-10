<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

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

        UserRole::create([
            'user_id' => $user->id,
            'role_id' => 8
        ]);
    }
}
