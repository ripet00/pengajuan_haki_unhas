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
        Schema::create('biodatas_paten', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_paten_id')->constrained('submissions_paten')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('tempat_invensi');
            $table->date('tanggal_invensi');
            $table->text('uraian_singkat');
            $table->enum('status', ['pending', 'approved', 'denied'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('admins')->onDelete('set null');
            
            // Error flags for field-level validation
            $table->boolean('error_tempat_invensi')->default(false);
            $table->boolean('error_tanggal_invensi')->default(false);
            $table->boolean('error_uraian_singkat')->default(false);
            
            // Document tracking fields
            $table->boolean('document_submitted')->default(false);
            $table->timestamp('document_submitted_at')->nullable();
            $table->boolean('certificate_issued')->default(false);
            $table->timestamp('certificate_issued_at')->nullable();
            
            $table->timestamps();

            // Indexes for better performance
            $table->index('submission_paten_id');
            $table->index('user_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biodatas_paten');
    }
};
