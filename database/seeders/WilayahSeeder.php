<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WilayahSeeder extends Seeder
{
    /**
     * Run the database seeder.
     * Data Kode Wilayah sesuai Kepmendagri No 300.2.2-2138 Tahun 2025
     */
    public function run(): void
    {
        // Disable foreign key checks for better performance
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate table if exists
        DB::table('wilayah')->truncate();

        // Load all wilayah data from converted file
        $wilayahData = require __DIR__ . '/WilayahData.php';

        $this->command->info('Loading ' . count($wilayahData) . ' wilayah records...');

        // Insert data in chunks for better performance
        $chunkSize = 1000;
        $chunks = array_chunk($wilayahData, $chunkSize);

        foreach ($chunks as $index => $chunk) {
            DB::table('wilayah')->insert($chunk);
            $this->command->info('Inserted chunk ' . ($index + 1) . ' of ' . count($chunks));
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('Wilayah seeder completed successfully with ' . count($wilayahData) . ' records!');
    }
}