<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create default admin
        Admin::create([
            'name' => 'Super Admin',
            'nip_nidn_nidk_nim' => 'ADM001',
            'phone_number' => '081234567890',
            'password' => Hash::make('admin123'),
        ]);

        // Create sample users for testing
        User::create([
            'name' => 'John Doe',
            'phone_number' => '081234567891',
            'faculty' => 'Teknik Informatika',
            'status' => 'pending',
            'password' => Hash::make('user123'),
        ]);

        User::create([
            'name' => 'Jane Smith',
            'phone_number' => '081234567892',
            'faculty' => 'Fakultas Hukum',
            'status' => 'active',
            'password' => Hash::make('user123'),
        ]);
    }
}
