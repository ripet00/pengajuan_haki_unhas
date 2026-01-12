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

        // Pendamping Paten 1 - Fakultas Teknik
        Admin::firstOrCreate(
            ['nip_nidn_nidk_nim' => 'pendamping001'],
            [
                'name' => 'Dr. Niswar.',
                'phone_number' => '081234567893',
                'password' => Hash::make('pendamping123'),
                'role' => Admin::ROLE_PENDAMPING_PATEN,
                'fakultas' => 'Fakultas Teknik',
                'program_studi' => 'Teknik Elektro',
            ]
        );

        // Pendamping Paten 2 - Fakultas MIPA
        Admin::firstOrCreate(
            ['nip_nidn_nidk_nim' => 'pendamping002'],
            [
                'name' => 'Prof. Dr. Siti Aminah, M.Si.',
                'phone_number' => '081234567894',
                'password' => Hash::make('pendamping123'),
                'role' => Admin::ROLE_PENDAMPING_PATEN,
                'fakultas' => 'Fakultas Matematika dan Ilmu Pengetahuan Alam',
                'program_studi' => 'Kimia',
            ]
        );

        // Pendamping Paten 3 - Fakultas Kedokteran
        Admin::firstOrCreate(
            ['nip_nidn_nidk_nim' => 'pendamping003'],
            [
                'name' => 'Dr. Ir. Budi Santoso, M.Eng.',
                'phone_number' => '081234567895',
                'password' => Hash::make('pendamping123'),
                'role' => Admin::ROLE_PENDAMPING_PATEN,
                'fakultas' => 'Fakultas Kedokteran',
                'program_studi' => 'Farmasi',
            ]
        );
    }
}
