<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // tambahkan foreign key sekarang (admins harus sudah ada)
            $table->foreign('verified_by_admin_id')
                  ->references('id')
                  ->on('admins')
                  ->nullOnDelete(); // atau ->onDelete('set null')
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['verified_by_admin_id']);
        });
    }
};
