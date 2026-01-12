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
        Schema::table('submissions_paten', function (Blueprint $table) {
            // Pendamping Paten assignment
            $table->unsignedBigInteger('pendamping_paten_id')->nullable()->after('reviewed_by_admin_id');
            $table->timestamp('assigned_at')->nullable()->after('pendamping_paten_id');
            
            // Substance review fields
            $table->longText('substance_review_notes')->nullable()->after('assigned_at');
            $table->string('substance_review_file')->nullable()->after('substance_review_notes');
            $table->timestamp('substance_reviewed_at')->nullable()->after('substance_review_file');
            
            // Foreign key for Pendamping Paten
            $table->foreign('pendamping_paten_id')->references('id')->on('admins')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('submissions_paten', function (Blueprint $table) {
            $table->dropForeign(['pendamping_paten_id']);
            $table->dropColumn([
                'pendamping_paten_id',
                'assigned_at',
                'substance_review_notes',
                'substance_review_file',
                'substance_reviewed_at'
            ]);
        });
    }
};
