<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'Utama',
                'phone_number' => '08123456789',
                'address' => 'Jl. Admin No.1',
                'gender' => 'Male',
                'dob' => '1990-01-01',
                'role' => 'admin',
                'password' => Hash::make('password123'),
            ]
        );
        User::updateOrCreate(
            ['email' => 'employee@example.com'],
            [
                'first_name' => 'Employee',
                'last_name' => 'Utama',
                'phone_number' => '08123456789',
                'address' => 'Jl. Admin No.1',
                'gender' => 'Male',
                'dob' => '1990-01-01',
                'role' => 'employee',
                'password' => Hash::make('password123'),
            ]
        );
    }
}
