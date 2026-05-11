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
        Schema::table('master_legalitas_usaha', function (Blueprint $table) {
            // Drop indexes first for SQLite compatibility
            $table->dropIndex(['wajib']);
        });
        
        Schema::table('master_legalitas_usaha', function (Blueprint $table) {
            // Drop columns that are not needed
            $table->dropColumn(['deskripsi', 'instansi_penerbit', 'wajib']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_legalitas_usaha', function (Blueprint $table) {
            // Add back the removed columns
            $table->text('deskripsi')->nullable()->comment('Deskripsi legalitas');
            $table->string('instansi_penerbit', 255)->nullable()->comment('Instansi yang menerbitkan');
            $table->boolean('wajib')->default(false)->comment('Apakah wajib untuk semua usaha');
            
            // Add back indexes
            $table->index('wajib');
        });
    }
};