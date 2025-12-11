<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiodataPaten extends Model
{
    use HasFactory;

    protected $table = 'biodatas_paten';

    protected $fillable = [
        'submission_paten_id',
        'user_id',
        'tempat_ciptaan',
        'tanggal_ciptaan',
        'uraian_singkat',
        'status',
        'rejection_reason',
        'reviewed_at',
        'reviewed_by',
        'error_tempat_ciptaan',
        'error_tanggal_ciptaan',
        'error_uraian_singkat',
        'document_submitted',
        'document_submitted_at',
        'certificate_issued',
        'certificate_issued_at',
    ];

    protected $casts = [
        'tanggal_ciptaan' => 'date',
        'reviewed_at' => 'datetime',
        'document_submitted_at' => 'datetime',
        'certificate_issued_at' => 'datetime',
        'error_tempat_ciptaan' => 'boolean',
        'error_tanggal_ciptaan' => 'boolean',
        'error_uraian_singkat' => 'boolean',
        'document_submitted' => 'boolean',
        'certificate_issued' => 'boolean',
    ];

    /**
     * Relationship to SubmissionPaten (One-to-One)
     */
    public function submissionPaten()
    {
        return $this->belongsTo(SubmissionPaten::class, 'submission_paten_id');
    }

    /**
     * Relationship to User (Many-to-One)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship to BiodataPatenInventors (One-to-Many)
     */
    public function inventors()
    {
        return $this->hasMany(BiodataPatenInventor::class, 'biodata_paten_id');
    }

    /**
     * Relationship to Admin who reviewed (Many-to-One)
     */
    public function reviewedBy()
    {
        return $this->belongsTo(Admin::class, 'reviewed_by');
    }

    /**
     * Get the leader inventor
     */
    public function leader()
    {
        return $this->hasOne(BiodataPatenInventor::class, 'biodata_paten_id')->where('is_leader', true);
    }

    /**
     * Get non-leader inventors
     */
    public function nonLeaders()
    {
        return $this->hasMany(BiodataPatenInventor::class, 'biodata_paten_id')->where('is_leader', false);
    }

    /**
     * Check if biodata is approved
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    /**
     * Check if biodata is denied
     */
    public function isDenied()
    {
        return $this->status === 'denied';
    }

    /**
     * Check if biodata is pending
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if has any errors
     */
    public function hasErrors()
    {
        return $this->error_tempat_ciptaan || 
               $this->error_tanggal_ciptaan || 
               $this->error_uraian_singkat ||
               $this->inventors()->where(function($query) {
                   $query->where('error_name', true)
                         ->orWhere('error_nik', true)
                         ->orWhere('error_npwp', true)
                         ->orWhere('error_jenis_kelamin', true)
                         ->orWhere('error_pekerjaan', true)
                         ->orWhere('error_universitas', true)
                         ->orWhere('error_fakultas', true)
                         ->orWhere('error_program_studi', true)
                         ->orWhere('error_alamat', true)
                         ->orWhere('error_kelurahan', true)
                         ->orWhere('error_kecamatan', true)
                         ->orWhere('error_kota_kabupaten', true)
                         ->orWhere('error_provinsi', true)
                         ->orWhere('error_kode_pos', true)
                         ->orWhere('error_email', true)
                         ->orWhere('error_nomor_hp', true)
                         ->orWhere('error_kewarganegaraan', true);
               })->exists();
    }

    /**
     * Get count of inventors with errors
     */
    public function getInventorsWithErrorsCount()
    {
        return $this->inventors()->where(function($query) {
            $query->where('error_name', true)
                  ->orWhere('error_nik', true)
                  ->orWhere('error_npwp', true)
                  ->orWhere('error_jenis_kelamin', true)
                  ->orWhere('error_pekerjaan', true)
                  ->orWhere('error_universitas', true)
                  ->orWhere('error_fakultas', true)
                  ->orWhere('error_program_studi', true)
                  ->orWhere('error_alamat', true)
                  ->orWhere('error_kelurahan', true)
                  ->orWhere('error_kecamatan', true)
                  ->orWhere('error_kota_kabupaten', true)
                  ->orWhere('error_provinsi', true)
                  ->orWhere('error_kode_pos', true)
                  ->orWhere('error_email', true)
                  ->orWhere('error_nomor_hp', true)
                  ->orWhere('error_kewarganegaraan', true);
        })->count();
    }
}
