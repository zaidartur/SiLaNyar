<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisCairan extends Model
{
    use HasFactory;

    protected $table = 'jenis_cairan';
    public $timestamps = false;
    
    protected $fillable = [
        'kode_jenis_cairan',
        'nama',
        'batas_minimum',
        'batas_maksimum'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (!$model->kode_jenis_cairan) {
                $akhir = self::orderBy('kode_jenis_cairan', 'desc')->first();
                $lanjut = 1;

                if ($akhir) {
                    $nomorTerakhir = (int)substr($akhir->kode_jenis_cairan, -3);
                    $lanjut = $nomorTerakhir + 1;
                }

                $model->kode_jenis_cairan = 'JC-'.str_pad($lanjut, 3, '0', STR_PAD_LEFT);
            }
        });
    }
}
