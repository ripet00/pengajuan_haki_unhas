<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmissionPatenHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'submission_paten_id',
        'admin_id',
        'review_type',
        'action',
        'notes',
    ];

    /**
     * Get the submission paten that owns the history.
     */
    public function submissionPaten()
    {
        return $this->belongsTo(SubmissionPaten::class);
    }

    /**
     * Get the admin who performed the review.
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
