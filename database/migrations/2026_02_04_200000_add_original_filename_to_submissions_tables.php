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
        // Add original_filename column to submissions table
        Schema::table('submissions', function (Blueprint $table) {
            $table->string('original_filename')->nullable()->after('file_name');
        });

        // Add original_filename column to submissions_paten table
        Schema::table('submissions_paten', function (Blueprint $table) {
            $table->string('original_filename')->nullable()->after('file_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropColumn('original_filename');
        });

        Schema::table('submissions_paten', function (Blueprint $table) {
            $table->dropColumn('original_filename');
        });
    }
};
