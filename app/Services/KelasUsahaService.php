<?php

namespace App\Services;

use App\Models\MasterKelasUsaha;
use App\Models\Usaha;

class KelasUsahaService
{
    /**
     * Tentukan kelas usaha berdasarkan omset, aset, dan karyawan.
     */
    public static function tentukan($omsetBulanan, $aset, $karyawan = null): ?int
    {
        $omsetBulanan = floatval($omsetBulanan ?? 0);
        $aset         = floatval($aset ?? 0);
        $karyawan     = ($karyawan !== null && $karyawan !== '') ? intval($karyawan) : null;
        $omsetTahunan = $omsetBulanan * 12;

        $kelasUsaha = MasterKelasUsaha::where('is_active', true)
            ->orderBy('rank', 'asc')
            ->get();

        $matched = [];

        if ($karyawan !== null) {
            $k = $kelasUsaha->first(fn($kelas) => self::inRange($karyawan, $kelas->min_karyawan, $kelas->max_karyawan));
            if ($k) $matched[] = $k;
        }

        $o = $kelasUsaha->first(fn($kelas) => self::inRange($omsetTahunan, $kelas->min_omset_tahunan, $kelas->max_omset_tahunan));
        if ($o) $matched[] = $o;

        $a = $kelasUsaha->first(fn($kelas) => self::inRange($aset, $kelas->min_modal, $kelas->max_modal));
        if ($a) $matched[] = $a;

        if (!empty($matched)) {
            usort($matched, fn($x, $y) => ($x->rank ?? PHP_INT_MAX) <=> ($y->rank ?? PHP_INT_MAX));
            return $matched[0]->id;
        }

        return $kelasUsaha->first()?->id;
    }

    /**
     * Hitung ulang kelas usaha untuk semua data usaha yang ada.
     * Dipanggil setelah kriteria kelas usaha diubah.
     */
    public static function recalculateAll(): int
    {
        $usahaList = Usaha::all(['id', 'omset_bulanan_rp', 'aset_rp', 'karyawan']);
        $updated   = 0;

        foreach ($usahaList as $usaha) {
            $newKelas = self::tentukan($usaha->omset_bulanan_rp, $usaha->aset_rp, $usaha->karyawan);
            if ($usaha->id_kelas_usaha !== $newKelas) {
                Usaha::where('id', $usaha->id)->update(['id_kelas_usaha' => $newKelas]);
                $updated++;
            }
        }

        return $updated;
    }

    private static function inRange($value, $min, $max): bool
    {
        if ($value === null || $value === '') return false;

        $hasMin = $min !== null && $min !== '';
        $hasMax = $max !== null && $max !== '';

        if (!$hasMin && !$hasMax) return false;
        if ($hasMin && $value < $min) return false;
        if ($hasMax && $value > $max) return false;

        return true;
    }
}
