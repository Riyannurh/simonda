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
        Schema::create('master_legalitas_usaha', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100)->comment('Nama jenis legalitas');
            $table->string('kode', 20)->nullable()->comment('Kode legalitas');
            $table->text('deskripsi')->nullable()->comment('Deskripsi legalitas');
            $table->string('instansi_penerbit', 255)->nullable()->comment('Instansi yang menerbitkan');
            $table->boolean('wajib')->default(false)->comment('Apakah wajib untuk semua usaha');
            $table->boolean('is_active')->default(true)->comment('Status aktif');
            $table->timestamps();

            // Indexes
            $table->index('nama');
            $table->index('kode');
            $table->index('is_active');
            $table->index('wajib');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_legalitas_usaha');
    }
};