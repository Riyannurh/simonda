<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Legalitas extends Model
{
    protected $table = 'legalitas';
    
    protected $fillable = [
        'usaha_id',
        'jenis',
        'nomor',
    ];

    // Relasi ke Usaha
    public function usaha()
    {
        return $this->belongsTo(Usaha::class, 'usaha_id');
    }
}
