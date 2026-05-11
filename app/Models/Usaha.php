<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usaha extends Model
{
    protected $table = 'usaha';
    
    protected $fillable = [
        'id_pemilik',
        'id_pendata',
        'nama_usaha',
        'merek',
        'kecamatan_usaha',
        'desa_usaha',
        'alamat_usaha',
        'karyawan',
        'omset_bulanan_rp',
        'aset_rp',
        'id_kelas_usaha',
        'id_kategori_usaha',
    ];

    protected $casts = [
        'omset_bulanan_rp' => 'decimal:2',
        'aset_rp' => 'decimal:2',
        'karyawan' => 'integer',
    ];

    // Relasi ke Pemilik
    public function pemilik()
    {
        return $this->belongsTo(Pemilik::class, 'id_pemilik');
    }

    // Relasi ke User (Pendata)
    public function pendata()
    {
        return $this->belongsTo(User::class, 'id_pendata');
    }

    // Relasi ke Kelas Usaha
    public function kelasUsaha()
    {
        return $this->belongsTo(MasterKelasUsaha::class, 'id_kelas_usaha');
    }

    // Relasi ke Kategori Usaha
    public function kategoriUsaha()
    {
        return $this->belongsTo(KategoriUsaha::class, 'id_kategori_usaha');
    }

    // Relasi ke Legalitas
    public function legalitas()
    {
        return $this->hasMany(Legalitas::class, 'usaha_id');
    }

    // Relasi ke Sosial Media
    public function sosialMedia()
    {
        return $this->hasMany(SosialMedia::class, 'usaha_id');
    }
}
