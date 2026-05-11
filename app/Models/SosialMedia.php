<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SosialMedia extends Model
{
    protected $table = 'sosial_media';
    
    protected $fillable = [
        'usaha_id',
        'platform',
        'url',
    ];

    // Relasi ke Usaha
    public function usaha()
    {
        return $this->belongsTo(Usaha::class, 'usaha_id');
    }
}
