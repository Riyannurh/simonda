<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Usaha;
use App\Models\Pemilik;
use App\Models\Legalitas;
use App\Models\SosialMedia;
use App\Models\ActivityLog;
use App\Models\KategoriUsaha;
use App\Models\MasterKelasUsaha;
use App\Services\KelasUsahaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataUmkmController extends Controller
{
    public function index(Request $request)
    {
        $query = Usaha::with(['pemilik', 'kelasUsaha', 'kategoriUsaha', 'pendata'])
            ->orderBy('created_at', 'desc');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_usaha', 'like', '%' . $request->search . '%')
                  ->orWhereHas('pemilik', fn($p) => $p->where('nama', 'like', '%' . $request->search . '%'));
            });
        }
        if ($request->kelas)      $query->where('id_kelas_usaha', $request->kelas);
        if ($request->kecamatan)  $query->where('kecamatan_usaha', $request->kecamatan);
        if ($request->pendata)    $query->where('id_pendata', $request->pendata);
        if ($request->tgl_dari)   $query->whereDate('created_at', '>=', $request->tgl_dari);
        if ($request->tgl_sampai) $query->whereDate('created_at', '<=', $request->tgl_sampai);

        $dataUmkm      = $query->paginate(15)->withQueryString();
        $kategoriUsaha = KategoriUsaha::all();
        $kelasUsaha    = MasterKelasUsaha::where('is_active', true)->orderBy('rank')->get();
        $kecamatanList = Usaha::distinct()->pluck('kecamatan_usaha')->filter()->sort()->values();
        $pendataList   = \App\Models\User::whereIn('role', ['admin', 'pendamping'])->orderBy('name')->get();

        return view('back-end.admin.data-umkm.index', compact('dataUmkm', 'kategoriUsaha', 'kelasUsaha', 'kecamatanList', 'pendataList'));
    }

    public function getWilayah(Request $request)
    {
        $type = $request->type;
        $kode = $request->kode;

        // Validasi format kode wilayah
        if ($kode && !preg_match('/^[\d.]+$/', $kode)) {
            return response()->json([]);
        }

        $wilayah = [];
        
        if ($type == 'provinsi') {
            // Provinsi: kode 2 digit tanpa titik (11, 12, 13, dst)
            $wilayah = DB::table('wilayah')
                ->where('kode', 'NOT LIKE', '%.%')
                ->whereRaw('LENGTH(kode) = 2')
                ->orderBy('nama')
                ->get();
        } elseif ($type == 'kabupaten') {
            // Kabupaten: format 33.06 (5 karakter dengan titik)
            $wilayah = DB::table('wilayah')
                ->where('kode', 'LIKE', $kode . '.%')
                ->whereRaw('LENGTH(kode) = 5')
                ->orderBy('nama')
                ->get();
        } elseif ($type == 'kecamatan') {
            // Kecamatan: format 33.06.01 (8 karakter dengan titik)
            $wilayah = DB::table('wilayah')
                ->where('kode', 'LIKE', $kode . '.%')
                ->whereRaw('LENGTH(kode) = 8')
                ->orderBy('nama')
                ->get();
        } elseif ($type == 'desa') {
            // Desa: format 33.06.01.2001 (13 karakter dengan titik)
            $wilayah = DB::table('wilayah')
                ->where('kode', 'LIKE', $kode . '.%')
                ->whereRaw('LENGTH(kode) = 13')
                ->orderBy('nama')
                ->get();
        }
        
        return response()->json($wilayah);
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            
            // Validasi
            $request->validate([
                'nik'          => 'required|string|max:16|unique:pemilik,nik',
                'nama'         => 'required|string|max:255',
                'nama_usaha'   => 'required|string|max:255',
                'tanggal_lahir' => ['nullable', 'date', function($attr, $val, $fail) {
                    if ($val && \Carbon\Carbon::parse($val)->age < 17) {
                        $fail('Pemilik harus berusia minimal 17 tahun.');
                    }
                }],
            ]);
            
            // Resolve kode wilayah ke nama
            $provinsiNama   = $request->provinsi_pemilik
                ? optional(\App\Models\Wilayah::where('kode', $request->provinsi_pemilik)->first())->nama ?? $request->provinsi_pemilik
                : null;
            $kabupatenNama  = $request->kabupaten_pemilik
                ? optional(\App\Models\Wilayah::where('kode', $request->kabupaten_pemilik)->first())->nama ?? $request->kabupaten_pemilik
                : null;
            $kecamatanNama  = $request->kecamatan_pemilik
                ? optional(\App\Models\Wilayah::where('kode', $request->kecamatan_pemilik)->first())->nama ?? $request->kecamatan_pemilik
                : null;
            $desaNama       = $request->desa_pemilik
                ? optional(\App\Models\Wilayah::where('kode', $request->desa_pemilik)->first())->nama ?? $request->desa_pemilik
                : null;
            $kecamatanUsahaNama = $request->kecamatan_usaha
                ? optional(\App\Models\Wilayah::where('kode', $request->kecamatan_usaha)->first())->nama ?? $request->kecamatan_usaha
                : null;
            $desaUsahaNama  = $request->desa_usaha
                ? optional(\App\Models\Wilayah::where('kode', $request->desa_usaha)->first())->nama ?? $request->desa_usaha
                : null;

            // Simpan data pemilik
            $pemilik = Pemilik::create([
                'nik' => $request->nik,
                'nama' => $request->nama,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'hp' => $request->hp ? '+62' . $request->hp : null,
                'email' => $request->email,
                'provinsi_pemilik' => $provinsiNama,
                'kabupaten_pemilik' => $kabupatenNama,
                'kecamatan_pemilik' => $kecamatanNama,
                'desa_pemilik' => $desaNama,
                'alamat_pemilik' => $request->alamat_pemilik,
                'bpjs_ketenagakerjaan' => $request->has('bpjs_ketenagakerjaan'),
                'bpjs_kesehatan' => $request->has('bpjs_kesehatan'),
                'ikut_forum' => $request->has('ikut_forum'),
                'nama_forum' => $request->nama_forum,
                'jabatan_forum' => $request->jabatan_forum,
                'ikut_koperasi' => $request->has('ikut_koperasi'),
                'nama_koperasi' => $request->nama_koperasi,
                'jabatan_koperasi' => $request->jabatan_koperasi,
                'ikut_pelatihan' => $request->has('ikut_pelatihan'),
                'nama_pelatihan' => $request->nama_pelatihan,
            ]);
            
            // Tentukan kelas usaha berdasarkan omset dan aset
            $kelasUsahaId = KelasUsahaService::tentukan($request->omset_bulanan_rp, $request->aset_rp, $request->karyawan);
            
            // Simpan data usaha
            $usaha = Usaha::create([
                'id_pemilik' => $pemilik->id,
                'id_pendata' => auth()->id(),
                'nama_usaha' => $request->nama_usaha,
                'merek' => $request->merek,
                'kecamatan_usaha' => $kecamatanUsahaNama,
                'desa_usaha' => $desaUsahaNama,
                'alamat_usaha' => $request->alamat_usaha,
                'karyawan' => $request->karyawan,
                'omset_bulanan_rp' => $request->omset_bulanan_rp,
                'aset_rp' => $request->aset_rp,
                'id_kelas_usaha' => $kelasUsahaId,
                'id_kategori_usaha' => $request->id_kategori_usaha,
            ]);
            
            // Simpan data legalitas
            if ($request->has('legalitas_jenis')) {
                $legalitasJenis = $request->legalitas_jenis;
                $legalitasNomor = $request->legalitas_nomor;
                
                foreach ($legalitasJenis as $index => $jenis) {
                    if (!empty($jenis)) {
                        $usaha->legalitas()->create([
                            'jenis' => $jenis,
                            'nomor' => $legalitasNomor[$index] ?? null,
                        ]);
                    }
                }
            }
            
            // Simpan data sosial media
            if ($request->has('sosmed_platform')) {
                $sosmedPlatform = $request->sosmed_platform;
                $sosmedUrl = $request->sosmed_url;
                
                foreach ($sosmedPlatform as $index => $platform) {
                    if (!empty($platform)) {
                        $usaha->sosialMedia()->create([
                            'platform' => $platform,
                            'url' => $sosmedUrl[$index] ?? null,
                        ]);
                    }
                }
            }
            
            DB::commit();

            ActivityLog::record('created', 'Usaha', $usaha->id,
                auth()->user()->name . ' menambahkan UMKM "' . $usaha->nama_usaha . '"'
            );

            return response()->json([
                'success' => true,
                'message' => 'Data UMKM berhasil ditambahkan'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $usaha = Usaha::with(['pemilik', 'kelasUsaha', 'kategoriUsaha', 'pendata', 'legalitas', 'sosialMedia'])
            ->findOrFail($id);

        $pemilik = $usaha->pemilik;
        $legalitas = $usaha->legalitas;
        $sosialMedia = $usaha->sosialMedia;

        return view('back-end.admin.data-umkm.detail', compact('pemilik', 'usaha', 'legalitas', 'sosialMedia'));
    }

    public function detail($id)
    {
        try {
            $usaha = Usaha::with([
                'pemilik', 'kelasUsaha', 'kategoriUsaha', 'pendata',
                'legalitas', 'sosialMedia'
            ])->findOrFail($id);

            return response()->json([
                'success' => true,
                'pemilik' => $usaha->pemilik,
                'usaha' => [
                    'id'                   => $usaha->id,
                    'nama_usaha'           => $usaha->nama_usaha,
                    'merek'                => $usaha->merek,
                    'kategori_usaha'       => $usaha->kategoriUsaha->nama ?? null,
                    'kecamatan_usaha' => $usaha->kecamatan_usaha,
                    'desa_usaha'           => $usaha->desa_usaha,
                    'alamat_usaha'         => $usaha->alamat_usaha,
                    'karyawan'             => $usaha->karyawan,
                    'omset_bulanan_rp'     => $usaha->omset_bulanan_rp,
                    'aset_rp'              => $usaha->aset_rp,
                    'kelas_usaha'          => $usaha->kelasUsaha->nama ?? null,
                    'pendata'              => $usaha->pendata->name ?? null,
                    'tanggal_input'        => $usaha->created_at ? $usaha->created_at->format('d F Y H:i') : null,
                    'tanggal_update'       => $usaha->updated_at ? $usaha->updated_at->format('d F Y H:i') : null,
                ],
                'legalitas'   => $usaha->legalitas,
                'sosialMedia' => $usaha->sosialMedia,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data'
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            $usaha = Usaha::with('pemilik', 'legalitas', 'sosialMedia')->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'pemilik' => $usaha->pemilik,
                'usaha' => $usaha,
                'legalitas' => $usaha->legalitas,
                'sosialMedia' => $usaha->sosialMedia,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data'
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            
            $usaha = Usaha::with('pemilik')->findOrFail($id);
            
            // Validasi
            $request->validate([
                'nik'          => 'required|string|max:16|unique:pemilik,nik,' . $usaha->pemilik->id,
                'nama'         => 'required|string|max:255',
                'nama_usaha'   => 'required|string|max:255',
                'tanggal_lahir' => ['nullable', 'date', function($attr, $val, $fail) {
                    if ($val && \Carbon\Carbon::parse($val)->age < 17) {
                        $fail('Pemilik harus berusia minimal 17 tahun.');
                    }
                }],
            ]);
            
            // Resolve kode wilayah ke nama
            $provinsiNama   = $request->provinsi_pemilik
                ? optional(\App\Models\Wilayah::where('kode', $request->provinsi_pemilik)->first())->nama ?? $request->provinsi_pemilik
                : null;
            $kabupatenNama  = $request->kabupaten_pemilik
                ? optional(\App\Models\Wilayah::where('kode', $request->kabupaten_pemilik)->first())->nama ?? $request->kabupaten_pemilik
                : null;
            $kecamatanNama  = $request->kecamatan_pemilik
                ? optional(\App\Models\Wilayah::where('kode', $request->kecamatan_pemilik)->first())->nama ?? $request->kecamatan_pemilik
                : null;
            $desaNama       = $request->desa_pemilik
                ? optional(\App\Models\Wilayah::where('kode', $request->desa_pemilik)->first())->nama ?? $request->desa_pemilik
                : null;
            $kecamatanUsahaNama = $request->kecamatan_usaha
                ? optional(\App\Models\Wilayah::where('kode', $request->kecamatan_usaha)->first())->nama ?? $request->kecamatan_usaha
                : null;
            $desaUsahaNama  = $request->desa_usaha
                ? optional(\App\Models\Wilayah::where('kode', $request->desa_usaha)->first())->nama ?? $request->desa_usaha
                : null;

            // Update data pemilik
            $usaha->pemilik->update([
                'nik' => $request->nik,
                'nama' => $request->nama,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'hp' => $request->hp ? '+62' . $request->hp : null,
                'email' => $request->email,
                'provinsi_pemilik' => $provinsiNama,
                'kabupaten_pemilik' => $kabupatenNama,
                'kecamatan_pemilik' => $kecamatanNama,
                'desa_pemilik' => $desaNama,
                'alamat_pemilik' => $request->alamat_pemilik,
                'bpjs_ketenagakerjaan' => $request->has('bpjs_ketenagakerjaan'),
                'bpjs_kesehatan' => $request->has('bpjs_kesehatan'),
                'ikut_forum' => $request->has('ikut_forum'),
                'nama_forum' => $request->nama_forum,
                'jabatan_forum' => $request->jabatan_forum,
                'ikut_koperasi' => $request->has('ikut_koperasi'),
                'nama_koperasi' => $request->nama_koperasi,
                'jabatan_koperasi' => $request->jabatan_koperasi,
                'ikut_pelatihan' => $request->has('ikut_pelatihan'),
                'nama_pelatihan' => $request->nama_pelatihan,
            ]);
            
            // Tentukan kelas usaha berdasarkan omset dan aset
            $kelasUsahaId = KelasUsahaService::tentukan($request->omset_bulanan_rp, $request->aset_rp, $request->karyawan);
            
            // Update data usaha
            $usaha->update([
                'nama_usaha' => $request->nama_usaha,
                'merek' => $request->merek,
                'kecamatan_usaha' => $kecamatanUsahaNama,
                'desa_usaha' => $desaUsahaNama,
                'alamat_usaha' => $request->alamat_usaha,
                'karyawan' => $request->karyawan,
                'omset_bulanan_rp' => $request->omset_bulanan_rp,
                'aset_rp' => $request->aset_rp,
                'id_kelas_usaha' => $kelasUsahaId,
                'id_kategori_usaha' => $request->id_kategori_usaha,
            ]);

            $usaha->legalitas()->delete();
            if ($request->has('legalitas_jenis')) {
                foreach ($request->legalitas_jenis as $index => $jenis) {
                    if (!empty($jenis)) {
                        $usaha->legalitas()->create([
                            'jenis' => $jenis,
                            'nomor' => $request->legalitas_nomor[$index] ?? null,
                        ]);
                    }
                }
            }

            $usaha->sosialMedia()->delete();
            if ($request->has('sosmed_platform')) {
                foreach ($request->sosmed_platform as $index => $platform) {
                    if (!empty($platform)) {
                        $usaha->sosialMedia()->create([
                            'platform' => $platform,
                            'url' => $request->sosmed_url[$index] ?? null,
                        ]);
                    }
                }
            }
             
            $usaha->load(['pemilik', 'kelasUsaha', 'kategoriUsaha']);

            DB::commit();

            ActivityLog::record('updated', 'Usaha', $usaha->id,
                auth()->user()->name . ' mengedit UMKM "' . $usaha->nama_usaha . '"'
            );

            return response()->json([
                'success' => true,
                'message' => 'Data UMKM berhasil diupdate',
                'item' => [
                    'id'               => $usaha->id,
                    'nama_pemilik'     => $usaha->pemilik->nama ?? null,
                    'nama_usaha'       => $usaha->nama_usaha,
                    'kelas_usaha_nama' => $usaha->kelasUsaha->nama ?? null,
                    'kategori_usaha_nama' => $usaha->kategoriUsaha->nama ?? null,
                    'kecamatan_usaha'  => $usaha->kecamatan_usaha,
                ],
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            $usaha = Usaha::with('pemilik')->findOrFail($id);
            
            $namaUsaha = $usaha->nama_usaha;
            $usahaId   = $usaha->id;

            // Hapus usaha (cascade akan menghapus legalitas dan sosial media)
            $usaha->delete();

            // Hapus pemilik
            $usaha->pemilik->delete();

            DB::commit();

            ActivityLog::record('deleted', 'Usaha', $usahaId,
                auth()->user()->name . ' menghapus UMKM "' . $namaUsaha . '"'
            );

            return response()->json([
                'success' => true,
                'message' => 'Data UMKM berhasil dihapus'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }

}
