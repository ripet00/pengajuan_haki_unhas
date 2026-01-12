<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    // Role constants
    const ROLE_SUPER_ADMIN = 'super_admin';
    const ROLE_ADMIN_PATEN = 'admin_paten';
    const ROLE_ADMIN_HAKCIPTA = 'admin_hakcipta';
    const ROLE_PENDAMPING_PATEN = 'pendamping_paten';

    protected $fillable = [
        'name',
        'nip_nidn_nidk_nim',
        'phone_number',
        'country_code',
        'password',
        'role',
        'fakultas',
        'program_studi',
        'is_active',
        'remember_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Get all available roles
     */
    public static function getRoles(): array
    {
        return [
            self::ROLE_SUPER_ADMIN => 'Super Admin',
            self::ROLE_ADMIN_PATEN => 'Admin Paten',
            self::ROLE_ADMIN_HAKCIPTA => 'Admin Hak Cipta',
            self::ROLE_PENDAMPING_PATEN => 'Pendamping Paten',
        ];
    }

    /**
     * Get role display name
     */
    public function getRoleNameAttribute(): string
    {
        $roles = self::getRoles();
        return $roles[$this->role] ?? 'Unknown';
    }

    /**
     * Check if admin is super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    /**
     * Check if admin can access Hak Cipta module
     */
    public function canAccessHakCipta(): bool
    {
        return in_array($this->role, [
            self::ROLE_SUPER_ADMIN,
            self::ROLE_ADMIN_HAKCIPTA
        ]);
    }

    /**
     * Check if admin can access Paten module
     */
    public function canAccessPaten(): bool
    {
        return in_array($this->role, [
            self::ROLE_SUPER_ADMIN,
            self::ROLE_ADMIN_PATEN
        ]);
    }

    /**
     * Check if admin can access Jenis Karya
     */
    public function canAccessJenisKarya(): bool
    {
        return in_array($this->role, [
            self::ROLE_SUPER_ADMIN,
            self::ROLE_ADMIN_HAKCIPTA
        ]);
    }

    /**
     * Check if admin can manage other admins
     */
    public function canManageAdmins(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    /**
     * Check if admin can access user management
     * All roles can access user management
     */
    public function canAccessUserManagement(): bool
    {
        return true; // All admin roles can access user management
    }

    /**
     * Check if admin is Pendamping Paten
     */
    public function isPendampingPaten(): bool
    {
        return $this->role === self::ROLE_PENDAMPING_PATEN;
    }

    /**
     * Get assigned paten submissions for Pendamping Paten
     */
    public function assignedPatenSubmissions()
    {
        return $this->hasMany(SubmissionPaten::class, 'pendamping_paten_id');
    }

    /**
     * Get count of active paten submissions being handled
     * (pending_substance_review or rejected_substance_review)
     */
    public function getActivePatenCountAttribute(): int
    {
        return $this->assignedPatenSubmissions()
            ->whereIn('status', ['pending_substance_review', 'rejected_substance_review'])
            ->count();
    }
}
