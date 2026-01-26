<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Biodata extends Model
{
    use HasFactory;

    protected $fillable = [
        'submission_id',
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
    ];

    /**
     * Relationship to Submission (One-to-One)
     */
    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }

    /**
     * Relationship to User (Many-to-One)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship to BiodataMembers (One-to-Many)
     */
    public function members()
    {
        return $this->hasMany(BiodataMember::class);
    }

    /**
     * Relationship to Admin who reviewed (Many-to-One)
     */
    public function reviewedBy()
    {
        return $this->belongsTo(Admin::class, 'reviewed_by');
    }

    /**
     * Get the leader member
     */
    public function leader()
    {
        return $this->hasOne(BiodataMember::class)->where('is_leader', true);
    }

    /**
     * Get non-leader members
     */
    public function nonLeaderMembers()
    {
        return $this->hasMany(BiodataMember::class)->where('is_leader', false);
    }

    /**
     * Check if biodata can be edited
     */
    public function canBeEdited()
    {
        return $this->status !== 'approved';
    }

    /**
     * Check if biodata is pending
     */
    public function isPending()
    {
        return $this->status === 'pending';
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
     * Get certificate processing deadline (1 week after document submitted)
     */
    public function getCertificateDeadline()
    {
        if ($this->document_submitted && $this->document_submitted_at) {
            return $this->document_submitted_at->addWeek();
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
