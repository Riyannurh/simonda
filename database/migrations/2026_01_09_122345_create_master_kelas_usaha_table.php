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
        Schema::create('master_kelas_usaha', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100)->comment('Nama kelas (Mikro, Kecil, Menengah)');
            $table->integer('rank')->nullable()->comment('Urutan ranking');
            $table->decimal('min_omset_tahunan', 15, 2)->nullable()->comment('Minimum omset tahunan');
            $table->decimal('max_omset_tahunan', 15, 2)->nullable()->comment('Maksimum omset tahunan');
            $table->decimal('min_modal', 15, 2)->nullable()->comment('Minimum modal');
            $table->decimal('max_modal', 15, 2)->nullable()->comment('Maksimum modal');
            $table->integer('min_karyawan')->nullable()->comment('Minimum jumlah karyawan');
            $table->integer('max_karyawan')->nullable()->comment('Maksimum jumlah karyawan');
            $table->boolean('is_active')->default(true)->comment('Status aktif');
            $table->timestamps();

            // Indexes
            $table->index('nama');
            $table->index('rank');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_kelas_usaha');
    }
};