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
        // Table already exists and migration is stuck, skipping.
        /*
        if (!Schema::hasTable('admin')) {
            Schema::create('admin', function (Blueprint $table) {
                $table->bigIncrements('id_admin');
                $table->string('nama', 100);
                $table->string('email', 255)->unique();
                $table->string('password', 255);
                $table->rememberToken();
                $table->timestampsTz();
            });
        }
        */
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin');
    }
};
