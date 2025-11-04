<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JenisKarya;

class JenisKaryaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenisKaryas = [
            'Buku',
            'Buku Saku',
            'Buku Panduan/Petunjuk',
            'Modul',
            'Booklet',
            'Karya tulis',
            'Artikel',
            'Disertasi',
            'Flyer',
            'Poster',
            'Leaflet',
            'Alat peraga',
            'Program komputer',
            'Karya rekaman video'
        ];

        foreach ($jenisKaryas as $nama) {
            JenisKarya::create([
                'nama' => $nama,
                'is_active' => true,
            ]);
        }
    }
}
