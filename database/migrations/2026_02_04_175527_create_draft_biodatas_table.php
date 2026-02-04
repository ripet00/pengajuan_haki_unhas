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
        Schema::create('draft_biodatas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained('submissions')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Biodata fields
            $table->string('tempat_ciptaan')->nullable();
            $table->date('tanggal_ciptaan')->nullable();
            $table->text('uraian_singkat')->nullable();
            
            // Leader member data (JSON)
            $table->json('leader_data')->nullable();
            
            // Additional members data (JSON array)
            $table->json('members_data')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('submission_id');
            $table->index('user_id');
            $table->unique(['submission_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('draft_biodatas');
    }
};
