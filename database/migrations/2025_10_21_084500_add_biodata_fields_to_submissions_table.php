<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            // Biodata stage tracking
            $table->enum('biodata_status', ['not_started', 'pending', 'approved', 'rejected'])->default('not_started')->after('status');
            $table->text('biodata_rejection_reason')->nullable()->after('biodata_status');
            $table->timestamp('biodata_submitted_at')->nullable()->after('biodata_rejection_reason');
            $table->timestamp('biodata_reviewed_at')->nullable()->after('biodata_submitted_at');
            $table->unsignedBigInteger('biodata_reviewed_by')->nullable()->after('biodata_reviewed_at');
            
            // Foreign key for biodata reviewer
            $table->foreign('biodata_reviewed_by')->references('id')->on('admins')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropForeign(['biodata_reviewed_by']);
            $table->dropColumn([
                'biodata_status',
                'biodata_rejection_reason', 
                'biodata_submitted_at',
                'biodata_reviewed_at',
                'biodata_reviewed_by'
            ]);
        });
    }
};