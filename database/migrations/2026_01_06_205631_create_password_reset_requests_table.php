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
        Schema::create('password_reset_requests', function (Blueprint $table) {
            $table->id();
            $table->enum('user_type', ['user', 'admin'])->comment('Type of account requesting reset');
            $table->string('phone_number')->comment('Phone number of the requester');
            $table->string('country_code', 5)->default('+62')->comment('Country code for phone number');
            $table->unsignedBigInteger('user_id')->nullable()->comment('ID from users or admins table');
            
            // Request metadata
            $table->enum('status', ['pending', 'sent', 'used', 'rejected', 'expired'])->default('pending');
            $table->timestamp('requested_at')->useCurrent()->comment('When the reset was requested');
            $table->string('request_ip', 45)->nullable()->comment('IP address of requester');
            $table->text('request_user_agent')->nullable()->comment('Browser user agent');
            
            // Token data (filled when admin approves)
            $table->string('token_hash')->nullable()->comment('Hashed reset token');
            $table->timestamp('token_created_at')->nullable()->comment('When token was generated');
            $table->timestamp('token_expires_at')->nullable()->comment('When token expires (60 minutes)');
            
            // Admin approval metadata
            $table->unsignedBigInteger('approved_by_admin_id')->nullable()->comment('Admin who approved the request');
            $table->timestamp('approved_at')->nullable()->comment('When request was approved');
            $table->enum('verification_method', ['call', 'wa', 'other'])->nullable()->comment('How admin verified user identity');
            $table->text('verification_notes')->nullable()->comment('Admin notes about verification');
            $table->string('admin_ip', 45)->nullable()->comment('IP address of approving admin');
            
            // Usage tracking
            $table->boolean('used')->default(false)->comment('Whether the token has been used');
            $table->timestamp('used_at')->nullable()->comment('When password was reset');
            $table->string('used_ip', 45)->nullable()->comment('IP address when password was reset');
            
            // Rejection tracking
            $table->unsignedBigInteger('rejected_by_admin_id')->nullable()->comment('Admin who rejected the request');
            $table->timestamp('rejected_at')->nullable()->comment('When request was rejected');
            $table->text('rejection_reason')->nullable()->comment('Reason for rejection');
            
            $table->timestamps();
            
            // Indexes
            $table->index(['phone_number', 'user_type']);
            $table->index('status');
            $table->index('token_expires_at');
            $table->index('requested_at');
            
            // Foreign keys
            $table->foreign('approved_by_admin_id')->references('id')->on('admins')->nullOnDelete();
            $table->foreign('rejected_by_admin_id')->references('id')->on('admins')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_reset_requests');
    }
};
