<?php

namespace App\Http\Controllers\KepalaBagian;

use App\Http\Controllers\Controller;
use App\Models\KategoriUsaha;
use App\Models\MasterKelasUsaha;
use App\Models\Usaha;
use Illuminate\Http\Request;

class DataUmkmController extends Controller
{
    public function index(Request $request)
    {
        $query = Usaha::with(['pemilik', 'kelasUsaha', 'kategoriUsaha'])
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
        $kelasUsaha    = MasterKelasUsaha::where('is_active', true)->orderBy('rank')->get();
        $kecamatanList = Usaha::distinct()->pluck('kecamatan_usaha')->filter()->sort()->values();

        return view('back-end.kepala-bagian.data-umkm.index', compact('dataUmkm', 'kelasUsaha', 'kecamatanList'));
    }

    public function detail($id)
    {
        try {
            $usaha = Usaha::with(['pemilik', 'kelasUsaha', 'kategoriUsaha', 'pendata', 'legalitas', 'sosialMedia'])
                ->findOrFail($id);

            return response()->json([
                'success'    => true,
                'pemilik'    => $usaha->pemilik,
                'usaha'      => [
                    'id'             => $usaha->id,
                    'nama_usaha'     => $usaha->nama_usaha,
                    'merek'          => $usaha->merek,
                    'kategori_usaha' => $usaha->kategoriUsaha->nama ?? null,
                    'kecamatan_usaha'=> $usaha->kecamatan_usaha,
                    'desa_usaha'     => $usaha->desa_usaha,
                    'alamat_usaha'   => $usaha->alamat_usaha,
                    'karyawan'       => $usaha->karyawan,
                    'omset_bulanan_rp' => $usaha->omset_bulanan_rp,
                    'aset_rp'        => $usaha->aset_rp,
                    'kelas_usaha'    => $usaha->kelasUsaha->nama ?? null,
                    'pendata'        => $usaha->pendata->name ?? null,
                    'tanggal_input'  => $usaha->created_at?->format('d F Y H:i'),
                    'tanggal_update' => $usaha->updated_at?->format('d F Y H:i'),
                ],
                'legalitas'  => $usaha->legalitas,
                'sosialMedia'=> $usaha->sosialMedia,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal memuat data'], 500);
        }
    }
}
