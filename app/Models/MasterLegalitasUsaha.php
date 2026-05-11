<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterLegalitasUsaha extends Model
{
    protected $table = 'master_legalitas_usaha';
    
    protected $fillable = [
        'nama',
        'kode',
        'is_active',
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
    ];
}
