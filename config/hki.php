<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Pejabat Pengalihan Hak Invensi
    |--------------------------------------------------------------------------
    |
    | Konfigurasi data pejabat yang bertanggung jawab untuk penandatanganan
    | surat pengalihan hak invensi dari inventor ke Universitas Hasanuddin.
    | Data ini akan digunakan dalam template Word surat pengalihan invensi.
    |
    */
    
    'pejabat_pengalihan' => [
        'nama' => 'Asmi Citra Malina, S.Pi., M.Agr., Ph.D.',
        'nip' => 'NIP 197212282006042001',
        'jabatan' => 'Direktur Inovasi dan Kekayaan Intelektual',
    ],

    /*
    |--------------------------------------------------------------------------
    | Materai Configuration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi text yang akan ditampilkan untuk materai
    | pada surat pengalihan invensi (hanya untuk inventor pertama)
    |
    */
    
    'materai' => [
        'text' => 'MATERAI Rp10.000',
    ],

    /*
    |--------------------------------------------------------------------------
    | Office Information
    |--------------------------------------------------------------------------
    |
    | Informasi kantor HKI Universitas Hasanuddin
    |
    */
    
    'office' => [
        'name' => 'Direktorat Inovasi dan Kekayaan Intelektual',
        'university' => 'Universitas Hasanuddin',
        'address' => 'Lt. 6 Gedung Rektorat',
        'street' => 'Jalan Perintis Kemerdekaan Km.10',
        'city' => 'Makassar',
        'postal_code' => '90245',
        'province' => 'Sulawesi Selatan',
        'country' => 'Indonesia',
        'phone' => '(0411) 586200',
        'email' => 'hki@unhas.ac.id',
    ],
];
