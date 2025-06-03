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
                $prefix = 'JC-';
                $akhir = self::where('kode_jenis_cairan', 'like', $prefix . '%')
                            ->orderBy('kode_jenis_cairan', 'desc')
                            ->first();

                $lanjut = 1;

                if ($akhir) {
                    $akhirKode = (int)substr($akhir->kode_jenis_cairan, -3);
                    $lanjut = $akhirKode + 1;
                }

                $model->kode_jenis_cairan = $prefix.str_pad($lanjut, 3, '0', STR_PAD_LEFT);
            }
        });
    }
}
