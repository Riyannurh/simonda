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
        Schema::create('usaha', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pemilik')->constrained('pemilik')->onDelete('cascade')->comment('Referensi ke pemilik.id');
            $table->foreignId('id_pendata')->constrained('users')->onDelete('restrict')->comment('Referensi ke users.id (yang input data)');
            $table->string('nama_usaha', 255)->comment('Nama usaha');
            $table->string('merek', 255)->nullable()->comment('Nama merek/brand');
            $table->string('jenis_usaha', 255)->nullable()->comment('Jenis/bidang usaha');
            $table->string('kecamatan_usaha', 100)->nullable()->comment('Kecamatan lokasi usaha');
            $table->string('desa_usaha', 100)->nullable()->comment('Desa/kelurahan lokasi usaha');
            $table->text('alamat_usaha')->nullable()->comment('Alamat lengkap usaha');
            $table->integer('karyawan')->nullable()->comment('Jumlah karyawan');
            $table->decimal('omset_bulanan_rp', 15, 2)->nullable()->comment('Omset per bulan dalam Rupiah');
            $table->decimal('aset_rp', 15, 2)->nullable()->comment('Total aset dalam Rupiah');
            $table->foreignId('id_kelas_usaha')->nullable()->constrained('master_kelas_usaha')->onDelete('set null')->comment('Referensi ke master_kelas_usaha.id');
            $table->foreignId('id_kategori_usaha')->nullable()->constrained('kategori_usaha')->onDelete('set null')->comment('Referensi ke kategori_usaha.id');
            $table->timestamps();

            // Indexes
            $table->index('nama_usaha');
            $table->index('jenis_usaha');
            $table->index('kecamatan_usaha');
            $table->index('id_pemilik');
            $table->index('id_pendata');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usaha');
    }
};