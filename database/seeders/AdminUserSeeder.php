<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'username' => 'superadmin',
            'password' => bcrypt('password'), // Encrypt your password, never store raw
            'firstname' => 'Super',
            'lastname' => 'Admin'
        ]);
    
        UserRole::create([
            'user_id' => $user->id,
            'role_id' => 12
        ]);
    }
}
