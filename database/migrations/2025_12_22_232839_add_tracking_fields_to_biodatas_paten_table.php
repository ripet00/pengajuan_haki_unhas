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
            // Add document submission tracking fields if they don't exist
            if (!Schema::hasColumn('biodatas_paten', 'document_submitted')) {
                $table->boolean('document_submitted')->default(false)->after('reviewed_by');
                $table->timestamp('document_submitted_at')->nullable()->after('document_submitted');
            }
            
            // Add signing tracking fields (replacing certificate tracking for paten)
            $table->boolean('ready_for_signing')->default(false)->after('document_submitted_at');
            $table->timestamp('ready_for_signing_at')->nullable()->after('ready_for_signing');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('biodatas_paten', function (Blueprint $table) {
            $table->dropColumn(['ready_for_signing', 'ready_for_signing_at']);
            
            // Only drop document tracking if they were added by this migration
            if (Schema::hasColumn('biodatas_paten', 'document_submitted')) {
                $table->dropColumn(['document_submitted', 'document_submitted_at']);
            }
        });
    }
};
