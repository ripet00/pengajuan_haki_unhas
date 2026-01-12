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
        Schema::create('submission_paten_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_paten_id')->constrained('submissions_paten')->onDelete('cascade');
            $table->foreignId('admin_id')->constrained('admins')->onDelete('cascade');
            $table->string('review_type'); // 'format_review' or 'substance_review'
            $table->string('action'); // 'approved' or 'rejected'
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submission_paten_histories');
    }
};
