<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MasterLegalitasUsaha;
use App\Models\KategoriUsaha;
use App\Models\MasterKelasUsaha;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Master Legalitas Usaha
        $legalitas = [
            ['nama' => 'NIB (Nomor Induk Berusaha)', 'kode' => 'NIB', 'is_active' => true],
            ['nama' => 'SIUP (Surat Izin Usaha Perdagangan)', 'kode' => 'SIUP', 'is_active' => true],
            ['nama' => 'TDP (Tanda Daftar Perusahaan)', 'kode' => 'TDP', 'is_active' => true],
            ['nama' => 'NPWP (Nomor Pokok Wajib Pajak)', 'kode' => 'NPWP', 'is_active' => true],
            ['nama' => 'Sertifikat Halal', 'kode' => 'HALAL', 'is_active' => true],
            ['nama' => 'PIRT (Pangan Industri Rumah Tangga)', 'kode' => 'PIRT', 'is_active' => true],
            ['nama' => 'HKI (Hak Kekayaan Intelektual)', 'kode' => 'HKI', 'is_active' => true],
        ];

        foreach ($legalitas as $item) {
            MasterLegalitasUsaha::create($item);
        }

        // Kategori Usaha
        $kategori = [
            ['nama' => 'Kuliner'],
            ['nama' => 'Fashion'],
            ['nama' => 'Kerajinan'],
            ['nama' => 'Pertanian'],
            ['nama' => 'Peternakan'],
            ['nama' => 'Perikanan'],
            ['nama' => 'Jasa'],
            ['nama' => 'Perdagangan'],
            ['nama' => 'Teknologi'],
            ['nama' => 'Kesehatan'],
        ];

        foreach ($kategori as $item) {
            KategoriUsaha::create($item);
        }

        // Master Kelas Usaha
        $kelasUsaha = [
            [
                'nama' => 'Usaha Mikro',
                'rank' => 1,
                'min_omset_tahunan' => 0,
                'max_omset_tahunan' => 300000000,
                'min_modal' => 0,
                'max_modal' => 50000000,
                'min_karyawan' => 1,
                'max_karyawan' => 4,
                'is_active' => true,
            ],
            [
                'nama' => 'Usaha Kecil',
                'rank' => 2,
                'min_omset_tahunan' => 300000000,
                'max_omset_tahunan' => 2500000000,
                'min_modal' => 50000000,
                'max_modal' => 500000000,
                'min_karyawan' => 5,
                'max_karyawan' => 19,
                'is_active' => true,
            ],
            [
                'nama' => 'Usaha Menengah',
                'rank' => 3,
                'min_omset_tahunan' => 2500000000,
                'max_omset_tahunan' => 50000000000,
                'min_modal' => 500000000,
                'max_modal' => 10000000000,
                'min_karyawan' => 20,
                'max_karyawan' => 99,
                'is_active' => true,
            ],
        ];

        foreach ($kelasUsaha as $item) {
            MasterKelasUsaha::create($item);
        }
    }
}
