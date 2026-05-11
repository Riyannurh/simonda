<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PenggunaController extends Controller
{
    public function index()
    {
        $users = User::with('wilayah')->orderBy('created_at', 'desc')->paginate(10);
        $kecamatan = Wilayah::getKecamatanPurworejo();
        return view('back-end.admin.pengguna.index', compact('users', 'kecamatan'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nip' => 'required|string|max:50|unique:users,nip',
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email',
                'password' => 'required|string|min:8',
                'role' => 'required|in:admin,pendamping,kepala_bagian',
                'wilayah_kode_kecamatan' => 'nullable|string|max:13',
            ], [
                'nip.required' => 'NIP wajib diisi',
                'nip.unique' => 'NIP sudah terdaftar',
                'name.required' => 'Nama wajib diisi',
                'email.required' => 'Email wajib diisi',
                'email.email' => 'Format email tidak valid',
                'email.unique' => 'Email sudah terdaftar',
                'password.required' => 'Password wajib diisi',
                'password.min' => 'Password minimal 8 karakter',
                'role.required' => 'Role wajib dipilih',
            ]);

            $validated['password'] = Hash::make($validated['password']);
            $validated['email_verified_at'] = now();
            
            // Reset wilayah_kode_kecamatan jika role bukan pendamping
            if ($validated['role'] !== 'pendamping') {
                $validated['wilayah_kode_kecamatan'] = null;
            }

            User::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Pengguna berhasil ditambahkan'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        }
    }

    public function update(Request $request, User $pengguna)
    {
        try {
            $validated = $request->validate([
                'nip' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($pengguna->id)],
                'name' => 'required|string|max:255',
                'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($pengguna->id)],
                'password' => 'nullable|string|min:8',
                'role' => 'required|in:admin,pendamping,kepala_bagian',
                'wilayah_kode_kecamatan' => 'nullable|string|max:13',
            ], [
                'nip.required' => 'NIP wajib diisi',
                'nip.unique' => 'NIP sudah terdaftar',
                'name.required' => 'Nama wajib diisi',
                'email.required' => 'Email wajib diisi',
                'email.email' => 'Format email tidak valid',
                'email.unique' => 'Email sudah terdaftar',
                'password.min' => 'Password minimal 8 karakter',
                'role.required' => 'Role wajib dipilih',
            ]);

            // Update password only if provided
            if (!empty($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }
            
            // Reset wilayah_kode_kecamatan jika role bukan pendamping
            if ($validated['role'] !== 'pendamping') {
                $validated['wilayah_kode_kecamatan'] = null;
            }

            $pengguna->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Pengguna berhasil diperbarui'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        }
    }

    public function destroy(User $pengguna)
    {
        // Prevent deleting own account
        if ($pengguna->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menghapus akun sendiri'
            ], 400);
        }

        try {
            $pengguna->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Pengguna berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Pengguna tidak dapat dihapus'
            ], 400);
        }
    }
}
