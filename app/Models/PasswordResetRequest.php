<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class PasswordResetRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_type',
        'phone_number',
        'country_code',
        'user_id',
        'status',
        'requested_at',
        'request_ip',
        'request_user_agent',
        'token_hash',
        'token_created_at',
        'token_expires_at',
        'approved_by_admin_id',
        'approved_at',
        'verification_method',
        'verification_notes',
        'admin_ip',
        'used',
        'used_at',
        'used_ip',
        'rejected_by_admin_id',
        'rejected_at',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'requested_at' => 'datetime',
            'token_created_at' => 'datetime',
            'token_expires_at' => 'datetime',
            'approved_at' => 'datetime',
            'used_at' => 'datetime',
            'rejected_at' => 'datetime',
            'used' => 'boolean',
        ];
    }

    /**
     * Get the user who made this request (if user_type = 'user')
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the admin who made this request (if user_type = 'admin')
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'user_id');
    }

    /**
     * Get the admin who approved this request
     */
    public function approvedBy()
    {
        return $this->belongsTo(Admin::class, 'approved_by_admin_id');
    }

    /**
     * Get the admin who rejected this request
     */
    public function rejectedBy()
    {
        return $this->belongsTo(Admin::class, 'rejected_by_admin_id');
    }

    /**
     * Check if token is expired
     */
    public function isExpired(): bool
    {
        if (!$this->token_expires_at) {
            return false;
        }
        return now()->greaterThan($this->token_expires_at);
    }

    /**
     * Check if token is valid (not used, not expired)
     */
    public function isValid(): bool
    {
        return !$this->used && !$this->isExpired() && $this->status === 'sent';
    }

    /**
     * Verify token matches hash
     */
    public function verifyToken(string $plainToken): bool
    {
        if (!$this->token_hash) {
            return false;
        }
        return Hash::check($plainToken, $this->token_hash);
    }

    /**
     * Mark token as used
     */
    public function markAsUsed(string $ip = null): void
    {
        $this->update([
            'used' => true,
            'used_at' => now(),
            'used_ip' => $ip,
            'status' => 'used',
        ]);
    }

    /**
     * Mark request as expired
     */
    public function markAsExpired(): void
    {
        $this->update([
            'status' => 'expired',
        ]);
    }

    /**
     * Scope to get pending requests
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get sent (awaiting user action) requests
     */
    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    /**
     * Scope to get requests for a specific user type
     */
    public function scopeForUserType($query, string $type)
    {
        return $query->where('user_type', $type);
    }

    /**
     * Get formatted phone number with country code
     */
    public function getFullPhoneNumberAttribute(): string
    {
        return $this->country_code . ltrim($this->phone_number, '0');
    }
}
