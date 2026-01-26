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
        Schema::table('submissions', function (Blueprint $table) {
            $table->enum('file_type', ['pdf', 'video'])->default('pdf')->after('categories');
            $table->string('video_link')->nullable()->after('file_type');
            $table->string('creator_name')->after('video_link');
            $table->string('creator_whatsapp')->after('creator_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropColumn(['file_type', 'video_link', 'creator_name', 'creator_whatsapp']);
        });
    }
};
