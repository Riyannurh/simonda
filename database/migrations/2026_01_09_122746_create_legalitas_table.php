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
        Schema::create('legalitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usaha_id')->constrained('usaha')->onDelete('cascade')->comment('Referensi ke usaha.id');
            $table->string('jenis', 100)->nullable()->comment('SIUP, TDP, NIB, NPWP, dll');
            $table->string('nomor', 100)->nullable()->comment('Nomor dokumen');
            $table->timestamps();

            // Indexes
            $table->index('usaha_id');
            $table->index('jenis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('legalitas');
    }
};