<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DraftBiodata extends Model
{
    use HasFactory;

    protected $fillable = [
        'submission_id',
        'user_id',
        'tempat_ciptaan',
        'tanggal_ciptaan',
        'uraian_singkat',
        'member_count',
        'leader_data',
        'members_data',
    ];

    protected $casts = [
        'tanggal_ciptaan' => 'date',
        'leader_data' => 'array',
        'members_data' => 'array',
    ];

    /**
     * Relationship to Submission
     */
    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }

    /**
     * Relationship to User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get or create draft for a submission and user
     */
    public static function getOrCreateDraft($submissionId, $userId)
    {
        return static::firstOrCreate(
            [
                'submission_id' => $submissionId,
                'user_id' => $userId,
            ]
        );
    }

    /**
     * Clear draft after final submission
     */
    public function clearDraft()
    {
        $this->delete();
    }
}
