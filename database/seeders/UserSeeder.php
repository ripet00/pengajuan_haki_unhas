<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Active user
        User::create([
            'name' => 'Dr. Ahmad Kusuma',
            'phone_number' => '081111111111',
            'faculty' => 'Fakultas Kedokteran',
            'status' => 'active',
            'password' => Hash::make('password123'),
            'verified_at' => now(),
        ]);

        // Pending user
        User::create([
            'name' => 'Prof. Siti Maharani',
            'phone_number' => '081222222222',
            'faculty' => 'Fakultas Teknik',
            'status' => 'pending',
            'password' => Hash::make('password123'),
        ]);

        // Another pending user
        User::create([
            'name' => 'Dr. Budi Santoso',
            'phone_number' => '081333333333',
            'faculty' => 'Fakultas Ekonomi dan Bisnis',
            'status' => 'pending',
            'password' => Hash::make('password123'),
        ]);

        // Denied user
        User::create([
            'name' => 'Ir. Maya Sari',
            'phone_number' => '081444444444',
            'faculty' => 'Fakultas Pertanian',
            'status' => 'denied',
            'password' => Hash::make('password123'),
        ]);
    }
}
