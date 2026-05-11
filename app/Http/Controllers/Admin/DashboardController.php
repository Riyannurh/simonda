<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Usaha;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUmkm       = Usaha::count();
        $totalPendamping = User::where('role', 'pendamping')->count();
        $totalKaryawan   = Usaha::sum('karyawan');
        $totalOmset      = Usaha::sum('omset_bulanan_rp');

        $aktivitasTerbaru = ActivityLog::with('user')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('back-end.admin.dashboard.dashboard', compact(
            'totalUmkm', 'totalPendamping', 'totalKaryawan', 'totalOmset',
            'aktivitasTerbaru'
        ));
    }
}
