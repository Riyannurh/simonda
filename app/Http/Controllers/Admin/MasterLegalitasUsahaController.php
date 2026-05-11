<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterLegalitasUsaha;
use Illuminate\Http\Request;

class MasterLegalitasUsahaController extends Controller
{
    public function index()
    {
        $legalitas = MasterLegalitasUsaha::orderBy('nama')->paginate(10);
        return view('back-end.admin.master.legalitas-usaha.index', compact('legalitas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);
        
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        MasterLegalitasUsaha::create($validated);

        return redirect()->route('admin.legalitas-usaha.index')
            ->with('success', 'Data legalitas usaha berhasil ditambahkan');
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);
        
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        $legalitas = MasterLegalitasUsaha::findOrFail($id);
        $legalitas->update($validated);

        return redirect()->route('admin.legalitas-usaha.index')
            ->with('success', 'Data legalitas usaha berhasil diupdate');
    }

    public function destroy(string $id)
    {
        $legalitas = MasterLegalitasUsaha::findOrFail($id);
        $legalitas->delete();

        return redirect()->route('admin.legalitas-usaha.index')
            ->with('success', 'Data legalitas usaha berhasil dihapus');
    }
}
