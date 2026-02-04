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
            // File PDF untuk tahap akhir pengajuan paten
            $table->string('deskripsi_pdf')->nullable()->after('application_document');
            $table->string('klaim_pdf')->nullable()->after('deskripsi_pdf');
            $table->string('abstrak_pdf')->nullable()->after('klaim_pdf');
            $table->string('gambar_pdf')->nullable()->after('abstrak_pdf'); // Opsional
            
            // Timestamp untuk tracking upload file
            $table->timestamp('patent_documents_uploaded_at')->nullable()->after('gambar_pdf');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('biodatas_paten', function (Blueprint $table) {
            $table->dropColumn([
                'deskripsi_pdf',
                'klaim_pdf',
                'abstrak_pdf',
                'gambar_pdf',
                'patent_documents_uploaded_at',
            ]);
        });
    }
};
