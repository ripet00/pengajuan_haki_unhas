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
            $table->string('file_review_path')->nullable()->after('rejection_reason');
            $table->string('file_review_name')->nullable()->after('file_review_path');
            $table->timestamp('file_review_uploaded_at')->nullable()->after('file_review_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('submissions_paten', function (Blueprint $table) {
            $table->dropColumn(['file_review_path', 'file_review_name', 'file_review_uploaded_at']);
        });
    }
};
