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
        Schema::create('biodata_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('biodata_id');
            $table->string('name');
            $table->string('nik')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->string('universitas')->nullable();
            $table->string('fakultas')->nullable();
            $table->string('program_studi')->nullable();
            $table->text('alamat')->nullable();
            $table->string('kelurahan')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kota_kabupaten')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kode_pos')->nullable();
            $table->string('email')->nullable();
            $table->string('nomor_hp')->nullable();
            $table->string('kewarganegaraan')->nullable();
            $table->boolean('is_leader')->default(false);
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('biodata_id')->references('id')->on('biodatas')->cascadeOnDelete();

            // Index for better performance
            $table->index('biodata_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biodata_members');
    }
};