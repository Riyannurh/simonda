<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterKelasUsaha;
use App\Services\KelasUsahaService;
use Illuminate\Http\Request;

class KelasUsahaController extends Controller
{
    public function index()
    {
        $kelasUsaha = MasterKelasUsaha::orderBy('rank')->get();
        return view('back-end.admin.master.kelas-usaha.index', compact('kelasUsaha'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'rank' => 'nullable|integer',
            'min_omset_tahunan' => 'nullable|numeric|min:0',
            'max_omset_tahunan' => 'nullable|numeric|min:0',
            'min_modal' => 'nullable|numeric|min:0',
            'max_modal' => 'nullable|numeric|min:0',
            'min_karyawan' => 'nullable|integer|min:0',
            'max_karyawan' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ], [
            'nama.required' => 'Nama kelas usaha wajib diisi',
            'nama.max' => 'Nama kelas usaha maksimal 255 karakter',
            'min_omset_tahunan.numeric' => 'Minimal omset harus berupa angka',
            'max_omset_tahunan.numeric' => 'Maksimal omset harus berupa angka',
            'min_modal.numeric' => 'Minimal modal harus berupa angka',
            'max_modal.numeric' => 'Maksimal modal harus berupa angka',
        ]);

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        MasterKelasUsaha::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kelas usaha berhasil ditambahkan'
        ]);
    }

    public function update(Request $request, MasterKelasUsaha $kelasUsaha)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'rank' => 'nullable|integer',
            'min_omset_tahunan' => 'nullable|numeric|min:0',
            'max_omset_tahunan' => 'nullable|numeric|min:0',
            'min_modal' => 'nullable|numeric|min:0',
            'max_modal' => 'nullable|numeric|min:0',
            'min_karyawan' => 'nullable|integer|min:0',
            'max_karyawan' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ], [
            'nama.required' => 'Nama kelas usaha wajib diisi',
            'nama.max' => 'Nama kelas usaha maksimal 255 karakter',
            'min_omset_tahunan.numeric' => 'Minimal omset harus berupa angka',
            'max_omset_tahunan.numeric' => 'Maksimal omset harus berupa angka',
            'min_modal.numeric' => 'Minimal modal harus berupa angka',
            'max_modal.numeric' => 'Maksimal modal harus berupa angka',
        ]);

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        $kelasUsaha->update($validated);

        // Hitung ulang kelas usaha semua data UMKM karena kriteria berubah
        KelasUsahaService::recalculateAll();

        return response()->json([
            'success' => true,
            'message' => 'Kelas usaha berhasil diperbarui'
        ]);
    }

    public function destroy(MasterKelasUsaha $kelasUsaha)
    {
        try {
            $kelasUsaha->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Kelas usaha berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Kelas usaha tidak dapat dihapus karena masih digunakan'
            ], 400);
        }
    }
}
