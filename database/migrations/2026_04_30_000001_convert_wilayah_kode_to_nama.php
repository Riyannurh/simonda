<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Convert kode wilayah ke nama di tabel pemilik
        $pemiliks = DB::table('pemilik')->get();

        foreach ($pemiliks as $pemilik) {
            $updates = [];

            if ($pemilik->provinsi_pemilik && preg_match('/^\d{2}$/', $pemilik->provinsi_pemilik)) {
                $nama = DB::table('wilayah')->where('kode', $pemilik->provinsi_pemilik)->value('nama');
                if ($nama) $updates['provinsi_pemilik'] = $nama;
            }

            if ($pemilik->kabupaten_pemilik && preg_match('/^\d{2}\.\d{2}$/', $pemilik->kabupaten_pemilik)) {
                $nama = DB::table('wilayah')->where('kode', $pemilik->kabupaten_pemilik)->value('nama');
                if ($nama) $updates['kabupaten_pemilik'] = $nama;
            }

            if ($pemilik->kecamatan_pemilik && preg_match('/^\d{2}\.\d{2}\.\d{2}$/', $pemilik->kecamatan_pemilik)) {
                $nama = DB::table('wilayah')->where('kode', $pemilik->kecamatan_pemilik)->value('nama');
                if ($nama) $updates['kecamatan_pemilik'] = $nama;
            }

            if ($pemilik->desa_pemilik && preg_match('/^\d{2}\.\d{2}\.\d{2}\.\d{4}$/', $pemilik->desa_pemilik)) {
                $nama = DB::table('wilayah')->where('kode', $pemilik->desa_pemilik)->value('nama');
                if ($nama) $updates['desa_pemilik'] = $nama;
            }

            if (!empty($updates)) {
                DB::table('pemilik')->where('id', $pemilik->id)->update($updates);
            }
        }

        // Convert kode wilayah ke nama di tabel usaha
        $usahas = DB::table('usaha')->get();

        foreach ($usahas as $usaha) {
            $updates = [];

            if ($usaha->kecamatan_usaha && preg_match('/^\d{2}\.\d{2}\.\d{2}$/', $usaha->kecamatan_usaha)) {
                $nama = DB::table('wilayah')->where('kode', $usaha->kecamatan_usaha)->value('nama');
                if ($nama) $updates['kecamatan_usaha'] = $nama;
            }

            if ($usaha->desa_usaha && preg_match('/^\d{2}\.\d{2}\.\d{2}\.\d{4}$/', $usaha->desa_usaha)) {
                $nama = DB::table('wilayah')->where('kode', $usaha->desa_usaha)->value('nama');
                if ($nama) $updates['desa_usaha'] = $nama;
            }

            if (!empty($updates)) {
                DB::table('usaha')->where('id', $usaha->id)->update($updates);
            }
        }
    }

    public function down(): void
    {
        // Tidak bisa di-reverse karena kode asli tidak disimpan
    }
};
