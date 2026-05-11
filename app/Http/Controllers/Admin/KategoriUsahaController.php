<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriUsaha;
use Illuminate\Http\Request;

class KategoriUsahaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategoriUsaha = KategoriUsaha::latest()->paginate(10);
        return view('back-end.admin.master.kategori-usaha.index', compact('kategoriUsaha'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:kategori_usaha,nama',
        ], [
            'nama.required' => 'Nama kategori usaha wajib diisi',
            'nama.unique' => 'Nama kategori usaha sudah ada',
            'nama.max' => 'Nama kategori usaha maksimal 255 karakter',
        ]);

        $kategori = KategoriUsaha::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kategori usaha berhasil ditambahkan',
            'item' => [
                'id'   => $kategori->id,
                'nama' => $kategori->nama,
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KategoriUsaha $kategoriUsaha)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:kategori_usaha,nama,' . $kategoriUsaha->id,
        ], [
            'nama.required' => 'Nama kategori usaha wajib diisi',
            'nama.unique' => 'Nama kategori usaha sudah ada',
            'nama.max' => 'Nama kategori usaha maksimal 255 karakter',
        ]);

        $kategoriUsaha->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kategori usaha berhasil diperbarui',
            'item' => [
                'id' => $kategoriUsaha->id,
                'nama' => $kategoriUsaha->nama,
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KategoriUsaha $kategoriUsaha)
    {
        try {
            $kategoriUsaha->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Kategori usaha berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori usaha tidak dapat dihapus karena masih digunakan'
            ], 400);
        }
    }
}
