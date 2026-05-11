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
        Schema::create('sosial_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usaha_id')->constrained('usaha')->onDelete('cascade')->comment('Referensi ke usaha.id');
            $table->string('platform', 50)->nullable()->comment('Instagram, Facebook, TikTok, dll');
            $table->string('url', 500)->nullable()->comment('URL/username akun');
            $table->timestamps();

            // Indexes
            $table->index('usaha_id');
            $table->index('platform');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sosial_media');
    }
};