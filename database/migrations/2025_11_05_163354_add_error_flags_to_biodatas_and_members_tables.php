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
        // Add error flags to biodatas table
        Schema::table('biodatas', function (Blueprint $table) {
            $table->boolean('error_tempat_ciptaan')->default(false);
            $table->boolean('error_tanggal_ciptaan')->default(false);
            $table->boolean('error_uraian_singkat')->default(false);
        });

        // Add error flags to biodata_members table
        Schema::table('biodata_members', function (Blueprint $table) {
            $table->boolean('error_name')->default(false);
            $table->boolean('error_nik')->default(false);
            $table->boolean('error_pekerjaan')->default(false);
            $table->boolean('error_universitas')->default(false);
            $table->boolean('error_fakultas')->default(false);
            $table->boolean('error_program_studi')->default(false);
            $table->boolean('error_alamat')->default(false);
            $table->boolean('error_kelurahan')->default(false);
            $table->boolean('error_kecamatan')->default(false);
            $table->boolean('error_kota_kabupaten')->default(false);
            $table->boolean('error_provinsi')->default(false);
            $table->boolean('error_kode_pos')->default(false);
            $table->boolean('error_email')->default(false);
            $table->boolean('error_nomor_hp')->default(false);
            $table->boolean('error_kewarganegaraan')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('biodatas', function (Blueprint $table) {
            $table->dropColumn([
                'error_tempat_ciptaan',
                'error_tanggal_ciptaan', 
                'error_uraian_singkat'
            ]);
        });

        Schema::table('biodata_members', function (Blueprint $table) {
            $table->dropColumn([
                'error_name',
                'error_nik',
                'error_pekerjaan',
                'error_universitas',
                'error_fakultas',
                'error_program_studi',
                'error_alamat',
                'error_kelurahan',
                'error_kecamatan',
                'error_kota_kabupaten',
                'error_provinsi',
                'error_kode_pos',
                'error_email',
                'error_nomor_hp',
                'error_kewarganegaraan'
            ]);
        });
    }
};
