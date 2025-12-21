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
        'tempat_invensi',
        'tanggal_invensi',
        'status',
        'rejection_reason',
        'reviewed_at',
        'reviewed_by',
        'error_tempat_invensi',
        'error_tanggal_invensi',
        'document_submitted',
        'document_submitted_at',
        'certificate_issued',
        'certificate_issued_at',
    ];

    protected $casts = [
        'tanggal_invensi' => 'date',
        'reviewed_at' => 'datetime',
        'document_submitted_at' => 'datetime',
        'certificate_issued_at' => 'datetime',
        'error_tempat_invensi' => 'boolean',
        'error_tanggal_invensi' => 'boolean',
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
        return $this->error_tempat_invensi || 
               $this->error_tanggal_invensi || 
               $this->inventors()->where(function($query) {
                   $query->where('error_name', true)
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

    /**
     * Get document submission deadline (1 month after biodata approval)
     */
    public function getDocumentDeadline()
    {
        if ($this->isApproved() && $this->reviewed_at) {
            return $this->reviewed_at->addMonth();
        }
        return null;
    }

    /**
     * Check if document submission is overdue
     */
    public function isDocumentOverdue()
    {
        if (!$this->document_submitted && $this->getDocumentDeadline()) {
            return now()->isAfter($this->getDocumentDeadline());
        }
        return false;
    }

    /**
     * Get certificate processing deadline (2 weeks after document submitted)
     */
    public function getCertificateDeadline()
    {
        if ($this->document_submitted && $this->document_submitted_at) {
            return $this->document_submitted_at->addWeeks(2);
        }
        return null;
    }

    /**
     * Check if certificate processing is overdue
     */
    public function isCertificateOverdue()
    {
        if (!$this->certificate_issued && $this->getCertificateDeadline()) {
            return now()->isAfter($this->getCertificateDeadline());
        }
        return false;
    }

    /**
     * Get days remaining until document deadline
     */
    public function getDaysUntilDocumentDeadline()
    {
        if ($this->getDocumentDeadline()) {
            return (int) now()->diffInDays($this->getDocumentDeadline(), false);
        }
        return null;
    }

    /**
     * Get days remaining until certificate deadline
     */
    public function getDaysUntilCertificateDeadline()
    {
        if ($this->getCertificateDeadline()) {
            return (int) now()->diffInDays($this->getCertificateDeadline(), false);
        }
        return null;
    }
}
