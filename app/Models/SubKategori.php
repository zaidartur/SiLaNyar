<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubKategori extends Model
{
    use HasFactory;

    protected $table = 'subkategori';

    protected $fillable = [
        'nama'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $akhir = self::orderBy('kode_subkategori', 'desc')->first();
            $lanjut = 1;

            if ($akhir) {
                $nomorTerakhir = (int)substr($akhir->kode_subkategori, -3);
                $lanjut = $nomorTerakhir + 1;
            }

            $model->kode_subkategori = 'SK-' . str_pad($lanjut, 3, '0', STR_PAD_LEFT);
        });
    }

    public function parameter()
    {
        return $this->belongsToMany(ParameterUji::class, 'parameter_subkategori', 'id_subkategori', 'id_parameter')
                    ->withPivot('baku_mutu')
                    ->withTimestamps();
    }

    public function kategori()
    {
        return $this->belongsToMany(Kategori::class, 'kategori_subkategori', 'id_subkategori','id_kategori');    
    }
}
