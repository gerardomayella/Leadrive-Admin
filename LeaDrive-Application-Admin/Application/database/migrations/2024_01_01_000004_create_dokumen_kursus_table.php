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
        Schema::create('dokumen_kursus', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->text('ktp')->nullable();
            $table->text('izin_usaha')->nullable();
            $table->text('sertif_instruktur')->nullable();
            $table->text('dokumen_legal')->nullable();
            $table->bigInteger('id_request');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen_kursus');
    }
};

