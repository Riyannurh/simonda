<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriUsaha extends Model
{
    protected $table = 'kategori_usaha';
    
    protected $fillable = [
        'nama',
    ];
    
    // Relasi ke Usaha
    public function usaha()
    {
        return $this->hasMany(Usaha::class, 'id_kategori_usaha');
    }
}
