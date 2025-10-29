<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $table = 'submissions';

    protected $fillable = [
        'user_id',
        'title',
        'categories',
        'file_type',
        'youtube_link',
        'creator_name',
        'creator_whatsapp',
        'file_path',
        'file_name',
        'file_size',
        'status',
        'reviewed_at',
        'rejection_reason',
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
        'biodata_submitted_at' => 'datetime',
        'biodata_reviewed_at' => 'datetime',
        'file_size' => 'integer',
    ];

    // Relasi ke model User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke model Admin (yang mereview document)
    public function reviewedByAdmin()
    {
        return $this->belongsTo(Admin::class, 'reviewed_by_admin_id');
    }

    // Relasi ke model Admin (yang mereview biodata)
    public function biodataReviewedByAdmin()
    {
        return $this->belongsTo(Admin::class, 'biodata_reviewed_by');
    }

    /**
     * Find submissions with similar titles (case-insensitive)
     * @param string $title
     * @param int|null $excludeId - ID submission yang akan dikecualikan dari pencarian
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function findSimilarTitles($title, $excludeId = null)
    {
        $query = self::with(['user'])
            ->whereRaw('LOWER(title) = ?', [strtolower($title)])
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
        return self::findSimilarTitles($this->title, $this->id);
    }
}
