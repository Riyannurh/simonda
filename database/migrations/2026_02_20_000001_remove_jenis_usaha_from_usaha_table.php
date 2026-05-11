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
        Schema::table('usaha', function (Blueprint $table) {
            $table->dropIndex(['jenis_usaha']); // Drop index dulu
            $table->dropColumn('jenis_usaha');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usaha', function (Blueprint $table) {
            $table->string('jenis_usaha', 255)->nullable()->comment('Jenis/bidang usaha');
            $table->index('jenis_usaha');
        });
    }
};
