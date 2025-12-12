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
        Schema::table('biodatas_paten', function (Blueprint $table) {
            // Rename tempat_ciptaan to tempat_invensi
            $table->renameColumn('tempat_ciptaan', 'tempat_invensi');
            
            // Rename tanggal_ciptaan to tanggal_invensi
            $table->renameColumn('tanggal_ciptaan', 'tanggal_invensi');
            
            // Rename error flags
            $table->renameColumn('error_tempat_ciptaan', 'error_tempat_invensi');
            $table->renameColumn('error_tanggal_ciptaan', 'error_tanggal_invensi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('biodatas_paten', function (Blueprint $table) {
            // Rollback: rename back to original names
            $table->renameColumn('tempat_invensi', 'tempat_ciptaan');
            $table->renameColumn('tanggal_invensi', 'tanggal_ciptaan');
            $table->renameColumn('error_tempat_invensi', 'error_tempat_ciptaan');
            $table->renameColumn('error_tanggal_invensi', 'error_tanggal_ciptaan');
        });
    }
};
