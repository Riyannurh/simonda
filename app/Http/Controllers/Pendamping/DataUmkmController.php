<?php

namespace App\Http\Controllers\Pendamping;

use App\Http\Controllers\Admin\DataUmkmController as AdminDataUmkmController;
use App\Http\Controllers\Controller;
use App\Models\KategoriUsaha;
use App\Models\MasterKelasUsaha;
use App\Models\Usaha;
use App\Models\Pemilik;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataUmkmController extends Controller
{
    // Reuse helper dari admin controller
    private AdminDataUmkmController $admin;

    public function __construct()
    {
        $this->admin = new AdminDataUmkmController();
    }

    private function myQuery()
    {
        return Usaha::where('id_pendata', auth()->id());
    }

    public function index(Request $request)
    {
        $query = $this->myQuery()
            ->with(['pemilik', 'kelasUsaha', 'kategoriUsaha'])
            ->orderBy('created_at', 'desc');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_usaha', 'like', '%' . $request->search . '%')
                  ->orWhereHas('pemilik', fn($p) => $p->where('nama', 'like', '%' . $request->search . '%'));
            });
        }
        if ($request->kelas)      $query->where('id_kelas_usaha', $request->kelas);
        if ($request->kecamatan)  $query->where('kecamatan_usaha', $request->kecamatan);
        if ($request->tgl_dari)   $query->whereDate('created_at', '>=', $request->tgl_dari);
        if ($request->tgl_sampai) $query->whereDate('created_at', '<=', $request->tgl_sampai);

        $dataUmkm      = $query->paginate(15)->withQueryString();
        $kategoriUsaha = KategoriUsaha::all();
        $kelasUsaha    = MasterKelasUsaha::where('is_active', true)->orderBy('rank')->get();
        $kecamatanList = $this->myQuery()->distinct()->pluck('kecamatan_usaha')->filter()->sort()->values();

        return view('back-end.pendamping.data-umkm.index', compact('dataUmkm', 'kategoriUsaha', 'kelasUsaha', 'kecamatanList'));
    }

    // Delegate ke admin controller (tidak perlu duplikasi)
    public function getWilayah(Request $request)
    {
        return $this->admin->getWilayah($request);
    }

    public function store(Request $request)
    {
        return $this->admin->store($request);
    }

    public function detail($id)
    {
        // Pastikan hanya bisa lihat data miliknya
        $usaha = $this->myQuery()->where('id', $id)->firstOrFail();
        return $this->admin->detail($id);
    }

    public function edit($id)
    {
        $this->myQuery()->where('id', $id)->firstOrFail();
        return $this->admin->edit($id);
    }

    public function update(Request $request, $id)
    {
        $this->myQuery()->where('id', $id)->firstOrFail();
        return $this->admin->update($request, $id);
    }

    public function destroy($id)
    {
        $this->myQuery()->where('id', $id)->firstOrFail();
        return $this->admin->destroy($id);
    }
}
