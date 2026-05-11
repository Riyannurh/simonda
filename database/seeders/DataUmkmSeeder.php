<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Pemilik;
use App\Models\Usaha;
use App\Models\Legalitas;
use App\Models\SosialMedia;
use App\Models\KategoriUsaha;
use App\Models\MasterKelasUsaha;
use App\Models\MasterLegalitasUsaha;

class DataUmkmSeeder extends Seeder
{
    public function run(): void
    {
        $pendampingList = User::where('role', 'pendamping')->get();

        if ($pendampingList->isEmpty()) {
            $this->command->warn('Belum ada data pendamping. Jalankan PendampingSeeder terlebih dahulu.');
            return;
        }

        $kategoriList  = KategoriUsaha::all();
        $kelasList     = MasterKelasUsaha::where('is_active', true)->get();
        $legalitasList = MasterLegalitasUsaha::where('is_active', true)->get();

        if ($kategoriList->isEmpty() || $kelasList->isEmpty()) {
            $this->command->warn('Master data belum lengkap. Jalankan MasterDataSeeder terlebih dahulu.');
            return;
        }

        $namaUsahaSamples = [
            'Warung Makan', 'Toko Kelontong', 'Bengkel Motor', 'Konveksi',
            'Kerajinan Bambu', 'Budidaya Lele', 'Ternak Ayam', 'Toko Sembako',
            'Jasa Laundry', 'Percetakan', 'Toko Bangunan', 'Usaha Catering',
            'Toko Elektronik', 'Salon Kecantikan', 'Apotek', 'Toko Pakaian',
            'Usaha Bakso', 'Toko Sepatu', 'Jasa Fotografi', 'Toko Buah',
        ];

        $namaPemilikSamples = [
            'Budi Santoso', 'Siti Rahayu', 'Ahmad Fauzi', 'Dewi Lestari',
            'Hendra Wijaya', 'Rina Kusuma', 'Agus Prasetyo', 'Yuni Astuti',
            'Doni Setiawan', 'Fitri Handayani', 'Bambang Sugiarto', 'Wati Ningsih',
            'Eko Purnomo', 'Sri Wahyuni', 'Joko Susilo', 'Ani Marlina',
            'Rudi Hartono', 'Lina Sari', 'Wahyu Hidayat', 'Endah Purwanti',
        ];

        $desaSamples = ['Desa Maju', 'Desa Sejahtera', 'Desa Makmur', 'Desa Damai', 'Desa Indah'];

        $count = 0;

        foreach ($pendampingList as $index => $pendamping) {
            $namaUsaha  = $namaUsahaSamples[$index % count($namaUsahaSamples)];
            $namaPemilik = $namaPemilikSamples[$index % count($namaPemilikSamples)];
            $kategori   = $kategoriList[$index % $kategoriList->count()];
            $kelas      = $kelasList[0]; // default Usaha Mikro

            // Buat pemilik
            $nik = '33060' . str_pad($index + 1, 11, '0', STR_PAD_LEFT);

            // Resolve nama wilayah dari kode
            $kodeKecamatan = $pendamping->wilayah_kode_kecamatan;
            $kecamatanNama = optional(\App\Models\Wilayah::where('kode', $kodeKecamatan)->first())->nama ?? $kodeKecamatan;
            $kabupatenNama = optional(\App\Models\Wilayah::where('kode', '33.06')->first())->nama ?? 'Purworejo';
            $provinsiNama  = optional(\App\Models\Wilayah::where('kode', '33')->first())->nama ?? 'Jawa Tengah';

            $pemilik = Pemilik::firstOrCreate(
                ['nik' => $nik],
                [
                    'nama'              => $namaPemilik,
                    'tempat_lahir'      => 'Purworejo',
                    'tanggal_lahir'     => '1985-01-' . str_pad(($index % 28) + 1, 2, '0', STR_PAD_LEFT),
                    'hp'                => '08' . str_pad(1234560000 + $index, 10, '0', STR_PAD_LEFT),
                    'jenis_kelamin'     => $index % 2 === 0 ? 'L' : 'P',
                    'provinsi_pemilik'  => $provinsiNama,
                    'kabupaten_pemilik' => $kabupatenNama,
                    'kecamatan_pemilik' => $kecamatanNama,
                    'desa_pemilik'      => $desaSamples[$index % count($desaSamples)],
                    'alamat_pemilik'    => 'Jl. Contoh No. ' . ($index + 1),
                    'bpjs_ketenagakerjaan' => false,
                    'bpjs_kesehatan'    => true,
                    'ikut_forum'        => false,
                    'ikut_koperasi'     => false,
                    'ikut_pelatihan'    => false,
                ]
            );

            // Buat usaha
            $usaha = Usaha::firstOrCreate(
                ['nama_usaha' => $namaUsaha . ' ' . $namaPemilik],
                [
                    'id_pemilik'        => $pemilik->id,
                    'id_pendata'        => $pendamping->id,
                    'merek'             => $namaUsaha,
                    'kecamatan_usaha'   => $kecamatanNama,
                    'desa_usaha'        => $desaSamples[$index % count($desaSamples)],
                    'alamat_usaha'      => 'Jl. Usaha No. ' . ($index + 1),
                    'karyawan'          => rand(1, 4),
                    'omset_bulanan_rp'  => rand(1, 20) * 1000000,
                    'aset_rp'           => rand(5, 50) * 1000000,
                    'id_kelas_usaha'    => $kelas->id,
                    'id_kategori_usaha' => $kategori->id,
                ]
            );

            // Tambah legalitas NIB jika ada
            $nib = $legalitasList->firstWhere('kode', 'NIB');
            if ($nib) {
                Legalitas::firstOrCreate(
                    ['usaha_id' => $usaha->id, 'jenis' => $nib->id],
                    ['nomor' => 'NIB-' . str_pad($index + 1, 6, '0', STR_PAD_LEFT)]
                );
            }

            // Tambah sosial media
            SosialMedia::firstOrCreate(
                ['usaha_id' => $usaha->id, 'platform' => 'Instagram'],
                ['url' => '@umkm_' . strtolower(str_replace(' ', '_', $namaUsaha)) . '_' . ($index + 1)]
            );

            $count++;
        }

        $this->command->info("Berhasil membuat {$count} data UMKM untuk {$count} pendamping.");
    }
}
