<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';

    protected $fillable = [
        'kode_kategori',
        'nama',
        'harga'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $akhir = self::orderBy('kode_kategori', 'desc')->first();
            $lanjut = 1;

            if ($akhir) {
                $nomorTerakhir = (int)substr($akhir->kode_kategori, -3);
                $lanjut = $nomorTerakhir + 1;
            }

            $model->kode_kategori = 'DK-' . str_pad($lanjut, 3, '0', STR_PAD_LEFT);
        });
    }

    public function parameter()
    {
        return $this->belongsToMany(ParameterUji::class, 'parameter_kategori', 'id_kategori', 'id_parameter')
            ->withPivot('baku_mutu')
            ->withTimestamps();
    }

    public function form_pengajuan()
    {
        return $this->hasMany(FormPengajuan::class, 'id_kategori');
    }
}