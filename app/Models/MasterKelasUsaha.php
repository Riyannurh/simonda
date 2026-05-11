<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterKelasUsaha extends Model
{
    protected $table = 'master_kelas_usaha';
    
    protected $fillable = [
        'nama',
        'rank',
        'min_omset_tahunan',
        'max_omset_tahunan',
        'min_modal',
        'max_modal',
        'min_karyawan',
        'max_karyawan',
        'is_active',
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'min_omset_tahunan' => 'decimal:2',
        'max_omset_tahunan' => 'decimal:2',
        'min_modal' => 'decimal:2',
        'max_modal' => 'decimal:2',
    ];
}
