<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DraftBiodataPaten extends Model
{
    use HasFactory;

    protected $fillable = [
        'submission_paten_id',
        'user_id',
        'inventor_count',
        'leader_data',
        'inventors_data',
    ];

    protected $casts = [
        'leader_data' => 'array',
        'inventors_data' => 'array',
    ];

    /**
     * Relationship to SubmissionPaten
     */
    public function submissionPaten()
    {
        return $this->belongsTo(SubmissionPaten::class);
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
    public static function getOrCreateDraft($submissionPatenId, $userId)
    {
        return static::firstOrCreate(
            [
                'submission_paten_id' => $submissionPatenId,
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
