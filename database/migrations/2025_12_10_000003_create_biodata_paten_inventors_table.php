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
        Schema::create('biodata_paten_inventors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('biodata_paten_id');
            $table->string('name');
            $table->string('pekerjaan')->nullable();
            $table->string('universitas')->nullable();
            $table->string('fakultas')->nullable();
            $table->text('alamat')->nullable();
            $table->string('kelurahan')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kota_kabupaten')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kode_pos')->nullable();
            $table->string('email');
            $table->string('nomor_hp')->nullable();
            $table->string('kewarganegaraan')->nullable();
            $table->boolean('is_leader')->default(false);
            
            // Error flags for field-level validation
            $table->boolean('error_name')->default(false);
            $table->boolean('error_pekerjaan')->default(false);
            $table->boolean('error_universitas')->default(false);
            $table->boolean('error_fakultas')->default(false);
            $table->boolean('error_alamat')->default(false);
            $table->boolean('error_kelurahan')->default(false);
            $table->boolean('error_kecamatan')->default(false);
            $table->boolean('error_kota_kabupaten')->default(false);
            $table->boolean('error_provinsi')->default(false);
            $table->boolean('error_kode_pos')->default(false);
            $table->boolean('error_email')->default(false);
            $table->boolean('error_nomor_hp')->default(false);
            $table->boolean('error_kewarganegaraan')->default(false);
            
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('biodata_paten_id')->references('id')->on('biodatas_paten')->cascadeOnDelete();

            // Index for better performance
            $table->index('biodata_paten_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biodata_paten_inventors');
    }
};
