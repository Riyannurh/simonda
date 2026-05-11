<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Wilayah;

class PendampingSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua kecamatan di Kabupaten Purworejo (kode 33.06.xx = 8 karakter)
        $kecamatanList = Wilayah::where('kode', 'LIKE', '33.06.%')
            ->whereRaw('LENGTH(kode) = 8')
            ->orderBy('nama')
            ->get();

        if ($kecamatanList->isEmpty()) {
            $this->command->warn('Data kecamatan Purworejo belum ada. Jalankan WilayahSeeder terlebih dahulu.');
            return;
        }

        foreach ($kecamatanList as $kecamatan) {
            // Buat slug nama untuk username/email
            $slug = strtolower(str_replace([' ', "'", '.'], ['_', '', ''], $kecamatan->nama));

            User::firstOrCreate(
                ['email' => "pendamping.{$slug}@simonda.id"],
                [
                    'name'                     => 'Pendamping ' . $kecamatan->nama,
                    'nip'                      => '19' . substr(str_replace('.', '', $kecamatan->kode), -6),
                    'password'                 => Hash::make('password'),
                    'role'                     => 'pendamping',
                    'wilayah_kode_kecamatan'   => $kecamatan->kode,
                ]
            );
        }

        $this->command->info("Berhasil membuat {$kecamatanList->count()} akun pendamping untuk Kabupaten Purworejo.");
    }
}
