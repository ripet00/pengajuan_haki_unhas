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
        'status',
        'rejection_reason',
        'reviewed_at',
        'reviewed_by',
        'document_submitted',
        'document_submitted_at',
        'application_document',
        'document_issued_at',
        'deskripsi_pdf',
        'klaim_pdf',
        'abstrak_pdf',
        'gambar_pdf',
        'patent_documents_uploaded_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
        'document_submitted_at' => 'datetime',
        'document_issued_at' => 'datetime',
        'patent_documents_uploaded_at' => 'datetime',
        'document_submitted' => 'boolean',
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
     * Get document submission deadline (7 days after biodata approval)
     */
    public function getDocumentDeadline()
    {
        if ($this->isApproved() && $this->reviewed_at) {
            return $this->reviewed_at->addDays(7);
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
     * Get signing processing deadline (1 week after document submitted)
     */
    public function getSigningDeadline()
    {
        if ($this->document_submitted && $this->document_submitted_at) {
            return $this->document_submitted_at->addWeek();
        }
        return null;
    }

    /**
     * Check if signing processing is overdue
     */
    public function isSigningOverdue()
    {
        if (!$this->application_document && $this->getSigningDeadline()) {
            return now()->isAfter($this->getSigningDeadline());
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
     * Get days remaining until signing deadline
     */
    public function getDaysUntilSigningDeadline()
    {
        if ($this->getSigningDeadline()) {
            return (int) now()->diffInDays($this->getSigningDeadline(), false);
        }
        return null;
    }
    
    /**
     * Check if all required patent documents (PDF) are uploaded
     */
    public function hasAllRequiredDocuments()
    {
        return !empty($this->deskripsi_pdf) && 
               !empty($this->klaim_pdf) && 
               !empty($this->abstrak_pdf);
    }
    
    /**
     * Check if at least one patent document is uploaded
     */
    public function hasAnyPatentDocument()
    {
        return !empty($this->deskripsi_pdf) || 
               !empty($this->klaim_pdf) || 
               !empty($this->abstrak_pdf) || 
               !empty($this->gambar_pdf);
    }
    
    /**
     * Get patent documents upload progress (0-100%)
     */
    public function getPatentDocumentsProgress()
    {
        $uploaded = 0;
        if (!empty($this->deskripsi_pdf)) $uploaded++;
        if (!empty($this->klaim_pdf)) $uploaded++;
        if (!empty($this->abstrak_pdf)) $uploaded++;
        // gambar_pdf is optional, so not counted
        
        return round(($uploaded / 3) * 100);
    }
}
