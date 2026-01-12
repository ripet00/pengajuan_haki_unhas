<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, update any invalid role values to super_admin
        DB::statement("UPDATE admins SET role = 'super_admin' WHERE role NOT IN ('super_admin', 'admin_paten', 'admin_hakcipta')");
        
        // Then modify the enum to include pendamping_paten
        DB::statement("ALTER TABLE admins MODIFY COLUMN role ENUM('super_admin', 'admin_paten', 'admin_hakcipta', 'pendamping_paten') NOT NULL DEFAULT 'super_admin'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove pendamping_paten role (update to super_admin first)
        DB::statement("UPDATE admins SET role = 'super_admin' WHERE role = 'pendamping_paten'");
        
        // Then revert enum
        DB::statement("ALTER TABLE admins MODIFY COLUMN role ENUM('super_admin', 'admin_paten', 'admin_hakcipta') NOT NULL DEFAULT 'super_admin'");
    }
};
