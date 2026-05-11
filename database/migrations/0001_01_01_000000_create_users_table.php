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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nip', 50)->unique()->comment('Nomor Induk Pegawai');
            $table->string('name', 255)->comment('Nama lengkap pengguna');
            $table->string('email', 255)->unique()->comment('Email untuk login');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->comment('Password terenkripsi');
            $table->enum('role', ['admin', 'pendamping', 'kepala_bagian'])->comment('Role pengguna');
            $table->string('wilayah_kode_kecamatan', 13)->nullable()->comment('Kode kecamatan wilayah kerja');
            $table->string('photo')->nullable()->comment('Photo profile path');
            $table->rememberToken();
            $table->timestamps();

            // Indexes
            $table->index('nip');
            $table->index('role');
            $table->index('wilayah_kode_kecamatan');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
