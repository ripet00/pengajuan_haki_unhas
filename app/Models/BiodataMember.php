<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiodataMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'biodata_id',
        'name',
        'nik',
        'pekerjaan',
        'universitas',
        'fakultas',
        'program_studi',
        'alamat',
        'kelurahan',
        'kecamatan',
        'kota_kabupaten',
        'provinsi',
        'kode_pos',
        'email',
        'nomor_hp',
        'kewarganegaraan',
        'is_leader',
        'error_name',
        'error_nik',
        'error_pekerjaan',
        'error_universitas',
        'error_fakultas',
        'error_program_studi',
        'error_alamat',
        'error_kelurahan',
        'error_kecamatan',
        'error_kota_kabupaten',
        'error_provinsi',
        'error_kode_pos',
        'error_email',
        'error_nomor_hp',
        'error_kewarganegaraan',
    ];

    protected $casts = [
        'is_leader' => 'boolean',
    ];

    /**
     * Relationship to Biodata (Many-to-One)
     */
    public function biodata()
    {
        return $this->belongsTo(Biodata::class);
    }
}