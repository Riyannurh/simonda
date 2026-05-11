<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemilik extends Model
{
    protected $table = 'pemilik';
    
    protected $fillable = [
        'nik',
        'nama',
        'tempat_lahir',
        'tanggal_lahir',
        'hp',
        'email',
        'jenis_kelamin',
        'provinsi_pemilik',
        'kabupaten_pemilik',
        'kecamatan_pemilik',
        'desa_pemilik',
        'alamat_pemilik',
        'bpjs_ketenagakerjaan',
        'bpjs_kesehatan',
        'ikut_forum',
        'nama_forum',
        'jabatan_forum',
        'ikut_koperasi',
        'nama_koperasi',
        'jabatan_koperasi',
        'ikut_pelatihan',
        'nama_pelatihan',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'bpjs_ketenagakerjaan' => 'boolean',
        'bpjs_kesehatan' => 'boolean',
        'ikut_forum' => 'boolean',
        'ikut_koperasi' => 'boolean',
        'ikut_pelatihan' => 'boolean',
    ];

    // Relasi ke Usaha
    public function usaha()
    {
        return $this->hasMany(Usaha::class, 'id_pemilik');
    }
}
