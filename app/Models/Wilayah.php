<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    protected $table = 'wilayah';
    protected $primaryKey = 'kode';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'kode',
        'nama',
    ];
    
    // Helper untuk mendapatkan kecamatan di Purworejo
    public static function getKecamatanPurworejo()
    {
        // Kode wilayah Purworejo: 33.06 (Provinsi 33 = Jawa Tengah, Kabupaten 06 = Purworejo)
        // Kecamatan format: 33.06.xx (8 karakter dengan titik)
        return self::where('kode', 'LIKE', '33.06.%')
            ->whereRaw('LENGTH(kode) = 8') // Kode kecamatan 8 karakter (33.06.01)
            ->orderBy('nama')
            ->get();
    }
}
