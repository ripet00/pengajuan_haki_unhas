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
    const ROLE_ADMIN_HKI = 'admin_hki';
    const ROLE_ADMIN_PATEN = 'admin_paten';
    const ROLE_ADMIN_HAKCIPTA = 'admin_hakcipta';

    protected $fillable = [
        'name',
        'nip_nidn_nidk_nim',
        'phone_number',
        'country_code',
        'password',
        'role',
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
            self::ROLE_ADMIN_HKI => 'Admin HKI',
            self::ROLE_ADMIN_PATEN => 'Admin Paten',
            self::ROLE_ADMIN_HAKCIPTA => 'Admin Hak Cipta',
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
            self::ROLE_ADMIN_HKI,
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
            self::ROLE_ADMIN_HKI,
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
            self::ROLE_ADMIN_HKI,
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
}
