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
        // Add member_count column to draft_biodatas table
        Schema::table('draft_biodatas', function (Blueprint $table) {
            $table->integer('member_count')->default(0)->after('uraian_singkat');
        });
        
        // Add inventor_count column to draft_biodata_patens table
        Schema::table('draft_biodata_patens', function (Blueprint $table) {
            $table->integer('inventor_count')->default(0)->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('draft_biodatas', function (Blueprint $table) {
            $table->dropColumn('member_count');
        });
        
        Schema::table('draft_biodata_patens', function (Blueprint $table) {
            $table->dropColumn('inventor_count');
        });
    }
};
