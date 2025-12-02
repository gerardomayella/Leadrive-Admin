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
        Schema::table('users', function (Blueprint $table) {
            $table->string('nomor_hp')->nullable()->after('email');
            $table->enum('role', ['pemilik_kursus', 'admin'])->default('pemilik_kursus')->after('nomor_hp');
            $table->enum('status', ['pending', 'active', 'rejected'])->default('pending')->after('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nomor_hp', 'role', 'status']);
        });
    }
};

