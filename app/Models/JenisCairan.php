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
        'nama',
        'batas_minimum',
        'batas_maksimum'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model)
        {
            $akhir = self::max('id') ?? 0;
            $lanjut = $akhir + 1;

            $model->kode_jenis_cairan = 'JC-'.str_pad($lanjut, 3, '0', STR_PAD_LEFT);
        });
    }
}
