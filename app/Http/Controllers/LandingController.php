<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $totalUmkm      = \App\Models\Usaha::count();
        $totalKecamatan = \App\Models\Usaha::distinct('kecamatan_usaha')->count();
        $totalKaryawan  = \App\Models\Usaha::sum('karyawan');
        $totalPendamping = \App\Models\User::where('role', 'pendamping')->count();

        return view('landingpage.index', compact('totalUmkm', 'totalKecamatan', 'totalKaryawan', 'totalPendamping'));
    }
}
