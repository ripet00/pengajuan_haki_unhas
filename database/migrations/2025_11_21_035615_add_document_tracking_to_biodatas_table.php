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
        Schema::table('biodatas', function (Blueprint $table) {
            // Tracking untuk penyetoran berkas
            $table->boolean('document_submitted')->default(false)->after('status');
            $table->timestamp('document_submitted_at')->nullable()->after('document_submitted');
            
            // Tracking untuk penerbitan sertifikat
            $table->boolean('certificate_issued')->default(false)->after('document_submitted_at');
            $table->timestamp('certificate_issued_at')->nullable()->after('certificate_issued');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('biodatas', function (Blueprint $table) {
            $table->dropColumn([
                'document_submitted',
                'document_submitted_at',
                'certificate_issued',
                'certificate_issued_at'
            ]);
        });
    }
};
