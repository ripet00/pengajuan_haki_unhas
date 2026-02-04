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
        Schema::create('draft_biodata_patens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_paten_id')->constrained('submissions_paten')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Leader inventor data (JSON)
            $table->json('leader_data')->nullable();
            
            // Additional inventors data (JSON array)
            $table->json('inventors_data')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('submission_paten_id');
            $table->index('user_id');
            $table->unique(['submission_paten_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('draft_biodata_patens');
    }
};
