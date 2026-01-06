<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmissionPaten extends Model
{
    use HasFactory;

    protected $table = 'submissions_paten';

    protected $fillable = [
        'user_id',
        'judul_paten',
        'kategori_paten',
        'creator_name',
        'creator_whatsapp',
        'creator_country_code',
        'file_path',
        'file_name',
        'file_size',
        'status',
        'reviewed_at',
        'rejection_reason',
        'file_review_path',
        'file_review_name',
        'file_review_uploaded_at',
        'revisi',
        'reviewed_by_admin_id',
        'biodata_status',
        'biodata_rejection_reason',
        'biodata_submitted_at',
        'biodata_reviewed_at',
        'biodata_reviewed_by',
    ];

    protected $casts = [
        'revisi' => 'boolean',
        'reviewed_at' => 'datetime',
        'file_review_uploaded_at' => 'datetime',
        'biodata_submitted_at' => 'datetime',
        'biodata_reviewed_at' => 'datetime',
        'file_size' => 'integer',
    ];

    /**
     * Relationship to User (Many-to-One)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship to Admin who reviewed the submission (Many-to-One)
     */
    public function reviewedByAdmin()
    {
        return $this->belongsTo(Admin::class, 'reviewed_by_admin_id');
    }

    /**
     * Relationship to Admin who reviewed the biodata (Many-to-One)
     */
    public function biodataReviewedByAdmin()
    {
        return $this->belongsTo(Admin::class, 'biodata_reviewed_by');
    }

    /**
     * Relationship to BiodataPaten (One-to-One)
     */
    public function biodataPaten()
    {
        return $this->hasOne(BiodataPaten::class, 'submission_paten_id');
    }

    /**
     * Alias for biodataPaten (for consistency)
     */
    public function biodata()
    {
        return $this->biodataPaten();
    }

    /**
     * Check if submission is approved
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    /**
     * Check if submission is rejected
     */
    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if submission is pending
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if biodata is approved
     */
    public function isBiodataApproved()
    {
        return $this->biodata_status === 'approved';
    }

    /**
     * Check if user can create biodata
     * (submission must be approved and biodata not yet started or rejected)
     */
    public function canCreateBiodata()
    {
        return $this->isApproved() && 
               in_array($this->biodata_status, ['not_started', 'rejected']);
    }

    /**
     * Get formatted file size
     */
    public function getFormattedFileSizeAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
