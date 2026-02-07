<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmissionPaten extends Model
{
    use HasFactory;

    protected $table = 'submissions_paten';

    // Status constants
    const STATUS_PENDING_FORMAT_REVIEW = 'pending_format_review';
    const STATUS_REJECTED_FORMAT_REVIEW = 'rejected_format_review';
    const STATUS_APPROVED_FORMAT = 'approved_format';
    const STATUS_PENDING_SUBSTANCE_REVIEW = 'pending_substance_review';
    const STATUS_REJECTED_SUBSTANCE_REVIEW = 'rejected_substance_review';
    const STATUS_APPROVED_SUBSTANCE = 'approved_substance';

    protected $fillable = [
        'user_id',
        'judul_paten',
        'kategori_paten',
        'creator_name',
        'creator_whatsapp',
        'creator_country_code',
        'file_path',
        'file_name',
        'original_filename',
        'file_size',
        'status',
        'reviewed_at',
        'rejection_reason',
        'file_review_path',
        'file_review_name',
        'file_review_uploaded_at',
        'revisi',
        'reviewed_by_admin_id',
        'pendamping_paten_id',
        'assigned_at',
        'substance_review_notes',
        'substance_review_file',
        'substance_reviewed_at',
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
        'assigned_at' => 'datetime',
        'substance_reviewed_at' => 'datetime',
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
     * Relationship to Pendamping Paten who handles substance review (Many-to-One)
     */
    public function pendampingPaten()
    {
        return $this->belongsTo(Admin::class, 'pendamping_paten_id');
    }

    /**
     * Relationship to BiodataPaten (One-to-One)
     */
    public function biodataPaten()
    {
        return $this->hasOne(BiodataPaten::class, 'submission_paten_id');
    }

    /**
     * Relationship to SubmissionPatenHistory
     */
    public function histories()
    {
        return $this->hasMany(SubmissionPatenHistory::class)->orderBy('created_at', 'asc');
    }

    /**
     * Alias for biodataPaten (for consistency)
     */
    public function biodata()
    {
        return $this->biodataPaten();
    }

    /**
     * Check if submission is approved (substance review passed)
     */
    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED_SUBSTANCE;
    }

    /**
     * Check if submission is rejected (format or substance)
     */
    public function isRejected()
    {
        return in_array($this->status, [
            self::STATUS_REJECTED_FORMAT_REVIEW,
            self::STATUS_REJECTED_SUBSTANCE_REVIEW
        ]);
    }

    /**
     * Check if submission is pending (format or substance review)
     */
    public function isPending()
    {
        return in_array($this->status, [
            self::STATUS_PENDING_FORMAT_REVIEW,
            self::STATUS_PENDING_SUBSTANCE_REVIEW
        ]);
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
     * (substance must be approved and biodata not yet started or rejected)
     */
    public function canCreateBiodata()
    {
        return $this->status === self::STATUS_APPROVED_SUBSTANCE && 
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

    /**
     * Check if status is approved_format (ready for assignment)
     */
    public function isApprovedFormat()
    {
        return $this->status === self::STATUS_APPROVED_FORMAT;
    }

    /**
     * Check if status is pending_substance_review
     */
    public function isPendingSubstanceReview()
    {
        return $this->status === self::STATUS_PENDING_SUBSTANCE_REVIEW;
    }

    /**
     * Check if status is rejected_substance_review
     */
    public function isRejectedSubstanceReview()
    {
        return $this->status === self::STATUS_REJECTED_SUBSTANCE_REVIEW;
    }

    /**
     * Check if status is approved_substance
     */
    public function isApprovedSubstance()
    {
        return $this->status === self::STATUS_APPROVED_SUBSTANCE;
    }

    /**
     * Check if can be assigned to Pendamping Paten
     */
    public function canBeAssigned()
    {
        return $this->status === self::STATUS_APPROVED_FORMAT && !$this->pendamping_paten_id;
    }

    /**
     * Check if substance review is active (being handled by Pendamping Paten)
     */
    public function isSubstanceReviewActive()
    {
        return in_array($this->status, [
            self::STATUS_PENDING_SUBSTANCE_REVIEW, 
            self::STATUS_REJECTED_SUBSTANCE_REVIEW
        ]);
    }

    /**
     * Get all available statuses
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_PENDING_FORMAT_REVIEW => 'Menunggu Review Format',
            self::STATUS_REJECTED_FORMAT_REVIEW => 'Format Ditolak',
            self::STATUS_APPROVED_FORMAT => 'Format Disetujui',
            self::STATUS_PENDING_SUBSTANCE_REVIEW => 'Menunggu Review Substansi',
            self::STATUS_REJECTED_SUBSTANCE_REVIEW => 'Substansi Ditolak',
            self::STATUS_APPROVED_SUBSTANCE => 'Substansi Disetujui',
        ];
    }

    /**
     * Get status display name
     */
    public function getStatusNameAttribute(): string
    {
        $statuses = self::getStatuses();
        return $statuses[$this->status] ?? 'Unknown';
    }

    /**
     * Find submissions with similar titles (case-insensitive)
     * @param string $title - Judul paten yang akan dicari
     * @param int|null $excludeId - ID submission yang akan dikecualikan dari pencarian
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function findSimilarTitles($title, $excludeId = null)
    {
        $query = self::with(['user'])
            ->whereRaw('LOWER(judul_paten) = ?', [strtolower($title)])
            ->orderBy('created_at', 'asc'); // Urutkan berdasarkan tanggal pengajuan
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->get();
    }

    /**
     * Check if current submission has similar titles with previous submissions
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSimilarTitles()
    {
        return self::findSimilarTitles($this->judul_paten, $this->id);
    }
}
