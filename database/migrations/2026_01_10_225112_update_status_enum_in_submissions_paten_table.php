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
        // First, modify the enum to include all old and new statuses
        DB::statement("ALTER TABLE submissions_paten MODIFY COLUMN status ENUM(
            'pending', 
            'approved', 
            'rejected',
            'pending_format_review', 
            'rejected_format_review', 
            'approved_format', 
            'pending_substance_review', 
            'rejected_substance_review', 
            'approved_substance'
        ) NOT NULL DEFAULT 'pending'");
        
        // Then update existing status values to match new flow
        DB::statement("UPDATE submissions_paten SET status = 'pending_format_review' WHERE status = 'pending'");
        DB::statement("UPDATE submissions_paten SET status = 'rejected_format_review' WHERE status = 'rejected'");
        DB::statement("UPDATE submissions_paten SET status = 'approved_substance' WHERE status = 'approved'");
        
        // Finally, remove old statuses from enum
        DB::statement("ALTER TABLE submissions_paten MODIFY COLUMN status ENUM(
            'pending_format_review', 
            'rejected_format_review', 
            'approved_format', 
            'pending_substance_review', 
            'rejected_substance_review', 
            'approved_substance'
        ) NOT NULL DEFAULT 'pending_format_review'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert status values
        DB::statement("UPDATE submissions_paten SET status = 'pending' WHERE status IN ('pending_format_review', 'pending_substance_review')");
        DB::statement("UPDATE submissions_paten SET status = 'rejected' WHERE status IN ('rejected_format_review', 'rejected_substance_review')");
        DB::statement("UPDATE submissions_paten SET status = 'approved' WHERE status IN ('approved_format', 'approved_substance')");
        
        // Revert enum
        DB::statement("ALTER TABLE submissions_paten MODIFY COLUMN status ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending'");
    }
};
