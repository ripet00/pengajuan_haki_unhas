<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $table = 'submissions';

    protected $fillable = [
        'user_id',
        'file_path',
        'file_name',
        'file_size',
        'status',
        'reviewed_at',
        'rejection_reason',
        'revisi',
        'reviewed_by_admin_id',
    ];

    protected $casts = [
        'revisi' => 'boolean',
        'reviewed_at' => 'datetime',
        'file_size' => 'integer',
    ];

    // Relasi ke model User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke model Admin (yang mereview)
    public function reviewedByAdmin()
    {
        return $this->belongsTo(Admin::class, 'reviewed_by_admin_id');
    }
}
