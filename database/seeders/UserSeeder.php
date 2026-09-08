<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin Bapelit',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin121'),
            'role' => 'admin',
        ]);

        // User biasa
        User::create([
            'name' => 'User Bapelit',
            'username' => 'user',
            'email' => 'user@gmail.com',
            'password' => Hash::make('admin121'),
            'role' => 'user',
        ]);

        // Staff (user tambahan)
        User::create([
            'name' => 'Staff Bapelit',
            'username' => 'staffbapelit',
            'email' => 'staff@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);
    }
}