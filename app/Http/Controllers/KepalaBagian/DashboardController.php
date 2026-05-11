<?php

namespace App\Http\Controllers\KepalaBagian;

use App\Http\Controllers\Controller;
use App\Models\Usaha;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUmkm     = Usaha::count();
        $totalPendamping = User::where('role', 'pendamping')->count();
        $totalKaryawan = Usaha::sum('karyawan');
        $totalOmsetBulanan = Usaha::sum('omset_bulanan_rp');
        $totalOmsetTahunan = $totalOmsetBulanan * 12;

        $bulanIni  = Usaha::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
        $bulanLalu = Usaha::whereMonth('created_at', now()->subMonth()->month)->whereYear('created_at', now()->subMonth()->year)->count();

        // Chart tren 6 bulan
        $chartBulan = collect(range(5, 0))->map(function ($i) {
            $date = now()->subMonths($i);
            return [
                'bulan' => $date->translatedFormat('M Y'),
                'total' => Usaha::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
            ];
        });

        // Chart kelas usaha
        $chartKelas = Usaha::with('kelasUsaha')
            ->get()
            ->groupBy('id_kelas_usaha')
            ->map(fn($g) => [
                'nama'  => $g->first()->kelasUsaha->nama ?? 'Belum Ditentukan',
                'total' => $g->count(),
            ])->values();

        // Chart per pendamping (top 10)
        $chartPendamping = User::where('role', 'pendamping')
            ->withCount('usaha')
            ->orderByDesc('usaha_count')
            ->take(10)
            ->get()
            ->map(fn($u) => ['nama' => $u->name, 'total' => $u->usaha_count]);

        // Chart kecamatan
        $chartKecamatan = Usaha::selectRaw('kecamatan_usaha, count(*) as total')
            ->whereNotNull('kecamatan_usaha')
            ->groupBy('kecamatan_usaha')
            ->orderByDesc('total')
            ->take(10)
            ->get()
            ->map(fn($r) => ['kecamatan' => $r->kecamatan_usaha, 'total' => $r->total]);

        // Top 5 pendamping terbanyak input bulan ini
        $topPendamping = User::where('role', 'pendamping')
            ->withCount(['usaha as bulan_ini' => fn($q) => $q->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)])
            ->orderByDesc('bulan_ini')
            ->take(5)
            ->get();

        return view('back-end.kepala-bagian.dashboard.dashboard', compact(
            'totalUmkm', 'totalPendamping', 'totalKaryawan', 'totalOmsetBulanan', 'totalOmsetTahunan',
            'bulanIni', 'bulanLalu',
            'chartBulan', 'chartKelas', 'chartPendamping', 'chartKecamatan',
            'topPendamping'
        ));
    }
}
