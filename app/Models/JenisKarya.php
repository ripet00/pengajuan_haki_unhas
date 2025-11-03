<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisKarya extends Model
{
    protected $table = 'jenis_karyas';

    protected $fillable = [
        'nama',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relasi ke submissions
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    // Scope untuk jenis karya yang aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
