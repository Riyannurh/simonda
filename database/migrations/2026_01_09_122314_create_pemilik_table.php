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
        Schema::create('pemilik', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 16)->unique()->comment('NIK KTP');
            $table->string('nama', 255)->comment('Nama lengkap pemilik');
            $table->string('tempat_lahir', 100)->nullable()->comment('Tempat lahir');
            $table->date('tanggal_lahir')->nullable()->comment('Tanggal lahir');
            $table->string('hp', 15)->nullable()->comment('Nomor HP/WA');
            $table->string('email', 255)->nullable()->comment('Email pemilik');
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable()->comment('Jenis kelamin');
            $table->string('provinsi_pemilik', 100)->nullable()->comment('Nama provinsi');
            $table->string('kabupaten_pemilik', 100)->nullable()->comment('Nama kabupaten/kota');
            $table->string('kecamatan_pemilik', 100)->nullable()->comment('Nama kecamatan');
            $table->string('desa_pemilik', 100)->nullable()->comment('Nama desa/kelurahan');
            $table->text('alamat_pemilik')->nullable()->comment('Alamat lengkap');
            $table->boolean('bpjs_ketenagakerjaan')->default(false)->comment('Status BPJS Ketenagakerjaan');
            $table->boolean('bpjs_kesehatan')->default(false)->comment('Status BPJS Kesehatan');
            $table->boolean('ikut_forum')->default(false)->comment('Ikut forum usaha');
            $table->string('nama_forum', 255)->nullable()->comment('Nama forum yang diikuti');
            $table->string('jabatan_forum', 100)->nullable()->comment('Jabatan di forum');
            $table->boolean('ikut_koperasi')->default(false)->comment('Ikut koperasi');
            $table->string('nama_koperasi', 255)->nullable()->comment('Nama koperasi');
            $table->string('jabatan_koperasi', 100)->nullable()->comment('Jabatan di koperasi');
            $table->boolean('ikut_pelatihan')->default(false)->comment('Pernah ikut pelatihan');
            $table->string('nama_pelatihan', 255)->nullable()->comment('Nama pelatihan terakhir');
            $table->timestamps();

            // Indexes
            $table->index('nik');
            $table->index('nama');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemilik');
    }
};