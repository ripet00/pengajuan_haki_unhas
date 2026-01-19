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
        Schema::table('biodatas_paten', function (Blueprint $table) {
            // Hapus kolom lama
            $table->dropColumn(['ready_for_signing', 'ready_for_signing_at']);
            
            // Tambah kolom baru untuk dokumen permohonan
            $table->string('application_document')->nullable()->after('document_submitted_at');
            $table->timestamp('document_issued_at')->nullable()->after('application_document');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('biodatas_paten', function (Blueprint $table) {
            // Hapus kolom baru
            $table->dropColumn(['application_document', 'document_issued_at']);
            
            // Kembalikan kolom lama
            $table->boolean('ready_for_signing')->default(false);
            $table->timestamp('ready_for_signing_at')->nullable();
        });
    }
};
