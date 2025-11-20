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
    ];

    protected $casts = [
        'tanggal_ciptaan' => 'date',
        'reviewed_at' => 'datetime',
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
}
