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
        Schema::create('submissions_paten', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('judul_paten');
            $table->enum('kategori_paten', ['Paten', 'Paten Sederhana'])->default('Paten');
            $table->string('creator_name');
            $table->string('creator_whatsapp');
            $table->string('creator_country_code', 5)->default('+62');
            $table->string('file_path');
            $table->string('file_name');
            $table->unsignedBigInteger('file_size'); // in bytes
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('reviewed_at')->nullable();
            $table->longText('rejection_reason')->nullable();
            $table->boolean('revisi')->default(false);
            $table->unsignedBigInteger('reviewed_by_admin_id')->nullable();
            
            // Biodata tracking fields
            $table->enum('biodata_status', ['not_started', 'pending', 'approved', 'rejected'])->default('not_started');
            $table->text('biodata_rejection_reason')->nullable();
            $table->timestamp('biodata_submitted_at')->nullable();
            $table->timestamp('biodata_reviewed_at')->nullable();
            $table->unsignedBigInteger('biodata_reviewed_by')->nullable();
            
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('reviewed_by_admin_id')->references('id')->on('admins')->nullOnDelete();
            $table->foreign('biodata_reviewed_by')->references('id')->on('admins')->nullOnDelete();
            
            // Indexes for better performance
            $table->index('user_id');
            $table->index('status');
            $table->index('biodata_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions_paten');
    }
};
