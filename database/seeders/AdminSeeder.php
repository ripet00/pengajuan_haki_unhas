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
        // Super Admin - Full access
        Admin::firstOrCreate(
            ['nip_nidn_nidk_nim' => 'admin123'],
            [
                'name' => 'Super Admin',
                'phone_number' => '089876543210',
                'password' => Hash::make('password123'),
                'role' => Admin::ROLE_SUPER_ADMIN,
            ]
        );

        // Admin HKI - Access to both Paten & Hak Cipta + User Management
        Admin::firstOrCreate(
            ['nip_nidn_nidk_nim' => 'hki001'],
            [
                'name' => 'Admin HKI UNHAS',
                'phone_number' => '081234567890',
                'password' => Hash::make('hkiunhas123'),
                'role' => Admin::ROLE_ADMIN_HKI,
            ]
        );

        // Admin Paten - Access to Paten module + User Management
        Admin::firstOrCreate(
            ['nip_nidn_nidk_nim' => 'paten001'],
            [
                'name' => 'Admin Paten',
                'phone_number' => '081234567891',
                'password' => Hash::make('paten123'),
                'role' => Admin::ROLE_ADMIN_PATEN,
            ]
        );

        // Admin Hak Cipta - Access to Hak Cipta + Jenis Karya + User Management
        Admin::firstOrCreate(
            ['nip_nidn_nidk_nim' => 'hakcipta001'],
            [
                'name' => 'Admin Hak Cipta',
                'phone_number' => '081234567892',
                'password' => Hash::make('hakcipta123'),
                'role' => Admin::ROLE_ADMIN_HAKCIPTA,
            ]
        );
    }
}
