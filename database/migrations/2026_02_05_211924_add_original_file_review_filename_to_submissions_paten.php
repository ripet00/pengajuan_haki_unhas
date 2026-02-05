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
        Schema::table('submissions_paten', function (Blueprint $table) {
            $table->string('original_file_review_filename')->nullable()->after('file_review_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('submissions_paten', function (Blueprint $table) {
            $table->dropColumn('original_file_review_filename');
        });
    }
};
