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
        // Add original_filename to biodatas_paten table for application_document
        Schema::table('biodatas_paten', function (Blueprint $table) {
            $table->string('original_filename')->nullable()->after('application_document');
        });

        // Add original_substance_review_filename to submissions_paten table
        Schema::table('submissions_paten', function (Blueprint $table) {
            $table->string('original_substance_review_filename')->nullable()->after('substance_review_file');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('biodatas_paten', function (Blueprint $table) {
            $table->dropColumn('original_filename');
        });

        Schema::table('submissions_paten', function (Blueprint $table) {
            $table->dropColumn('original_substance_review_filename');
        });
    }
};
