<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmissionHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'submission_id',
        'admin_id',
        'action',
        'notes',
    ];

    /**
     * Get the submission that owns the history.
     */
    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }

    /**
     * Get the admin who performed the review.
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
