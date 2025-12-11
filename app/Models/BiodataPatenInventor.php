<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiodataPatenInventor extends Model
{
    use HasFactory;

    protected $table = 'biodata_paten_inventors';

    protected $fillable = [
        'biodata_paten_id',
        'name',
        'nik',
        'npwp',
        'jenis_kelamin',
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
        'error_npwp',
        'error_jenis_kelamin',
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
        'error_name' => 'boolean',
        'error_nik' => 'boolean',
        'error_npwp' => 'boolean',
        'error_jenis_kelamin' => 'boolean',
        'error_pekerjaan' => 'boolean',
        'error_universitas' => 'boolean',
        'error_fakultas' => 'boolean',
        'error_program_studi' => 'boolean',
        'error_alamat' => 'boolean',
        'error_kelurahan' => 'boolean',
        'error_kecamatan' => 'boolean',
        'error_kota_kabupaten' => 'boolean',
        'error_provinsi' => 'boolean',
        'error_kode_pos' => 'boolean',
        'error_email' => 'boolean',
        'error_nomor_hp' => 'boolean',
        'error_kewarganegaraan' => 'boolean',
    ];

    /**
     * Relationship to BiodataPaten (Many-to-One)
     */
    public function biodataPaten()
    {
        return $this->belongsTo(BiodataPaten::class, 'biodata_paten_id');
    }

    /**
     * Check if this inventor is the leader
     */
    public function isLeader()
    {
        return $this->is_leader;
    }

    /**
     * Check if this inventor has any errors
     */
    public function hasErrors()
    {
        return $this->error_name || 
               $this->error_nik || 
               $this->error_npwp || 
               $this->error_jenis_kelamin || 
               $this->error_pekerjaan || 
               $this->error_universitas || 
               $this->error_fakultas || 
               $this->error_program_studi || 
               $this->error_alamat || 
               $this->error_kelurahan || 
               $this->error_kecamatan || 
               $this->error_kota_kabupaten || 
               $this->error_provinsi || 
               $this->error_kode_pos || 
               $this->error_email || 
               $this->error_nomor_hp || 
               $this->error_kewarganegaraan;
    }

    /**
     * Get array of error fields
     */
    public function getErrorFields()
    {
        $errors = [];
        
        if ($this->error_name) $errors[] = 'name';
        if ($this->error_nik) $errors[] = 'nik';
        if ($this->error_npwp) $errors[] = 'npwp';
        if ($this->error_jenis_kelamin) $errors[] = 'jenis_kelamin';
        if ($this->error_pekerjaan) $errors[] = 'pekerjaan';
        if ($this->error_universitas) $errors[] = 'universitas';
        if ($this->error_fakultas) $errors[] = 'fakultas';
        if ($this->error_program_studi) $errors[] = 'program_studi';
        if ($this->error_alamat) $errors[] = 'alamat';
        if ($this->error_kelurahan) $errors[] = 'kelurahan';
        if ($this->error_kecamatan) $errors[] = 'kecamatan';
        if ($this->error_kota_kabupaten) $errors[] = 'kota_kabupaten';
        if ($this->error_provinsi) $errors[] = 'provinsi';
        if ($this->error_kode_pos) $errors[] = 'kode_pos';
        if ($this->error_email) $errors[] = 'email';
        if ($this->error_nomor_hp) $errors[] = 'nomor_hp';
        if ($this->error_kewarganegaraan) $errors[] = 'kewarganegaraan';
        
        return $errors;
    }
}
