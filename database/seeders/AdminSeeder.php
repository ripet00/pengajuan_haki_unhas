<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'name' => 'Super Admin',
            'nip_nidn_nidk_nim' => 'admin123',
            'phone_number' => '081234567890',
            'password' => Hash::make('password123'),
        ]);

        Admin::create([
            'name' => 'Admin HAKI UNHAS',
            'nip_nidn_nidk_nim' => 'haki001',
            'phone_number' => '081987654321',
            'password' => Hash::make('hakiunhas123'),
        ]);
    }
}
