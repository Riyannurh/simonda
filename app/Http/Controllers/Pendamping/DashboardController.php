<?php

namespace App\Http\Controllers\Pendamping;

use App\Http\Controllers\Controller;
use App\Models\Usaha;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $dataUmkm = Usaha::with(['pemilik', 'kelasUsaha', 'kategoriUsaha'])
            ->where('id_pendata', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalUmkm          = $dataUmkm->count();
        $totalKaryawan      = $dataUmkm->sum('karyawan');
        $totalOmset         = $dataUmkm->sum('omset_bulanan_rp');
        $totalOmsetTahunan  = $totalOmset * 12;

        $bulanIni  = $dataUmkm->filter(fn($u) => $u->created_at && $u->created_at->isCurrentMonth())->count();
        $bulanLalu = $dataUmkm->filter(fn($u) => $u->created_at && $u->created_at->isLastMonth())->count();

        $recentUmkm = Usaha::with(['pemilik', 'kelasUsaha', 'kategoriUsaha'])
            ->where('id_pendata', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        $chartKelas = $dataUmkm->groupBy('id_kelas_usaha')->map(fn($g) => [
            'nama'  => $g->first()->kelasUsaha->nama ?? 'Belum Ditentukan',
            'total' => $g->count(),
        ])->values();

        $chartBulan = collect(range(5, 0))->map(function ($i) use ($userId) {
            $date = now()->subMonths($i);
            return [
                'bulan' => $date->translatedFormat('M Y'),
                'total' => Usaha::where('id_pendata', $userId)
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
            ];
        });

        return view('back-end.pendamping.dashboard.dashboard', compact(
            'totalUmkm', 'totalKaryawan', 'totalOmset', 'totalOmsetTahunan',
            'bulanIni', 'bulanLalu', 'recentUmkm',
            'chartKelas', 'chartBulan'
        ));
    }
}
