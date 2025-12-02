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
        Schema::create('paket_kursus', function (Blueprint $table) {
            $table->bigInteger('id_paket')->primary();
            $table->string('nama_paket');
            $table->decimal('harga', 10, 2);
            $table->integer('durasi_jam');
            $table->text('deskripsi')->nullable();
            $table->bigInteger('id_request');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paket_kursus');
    }
};

