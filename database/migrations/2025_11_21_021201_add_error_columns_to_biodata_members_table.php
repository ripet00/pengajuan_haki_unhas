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
        Schema::table('biodata_members', function (Blueprint $table) {
            $table->boolean('error_npwp')->default(false)->after('error_nik');
            $table->boolean('error_jenis_kelamin')->default(false)->after('error_npwp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('biodata_members', function (Blueprint $table) {
            $table->dropColumn(['error_npwp', 'error_jenis_kelamin']);
        });
    }
};
