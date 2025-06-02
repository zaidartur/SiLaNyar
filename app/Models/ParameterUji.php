<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParameterUji extends Model
{
    use HasFactory;

    protected $table = 'parameter_uji';

    protected $fillable = [
        'kode_parameter',
        'nama_parameter',
        'satuan',
        'harga',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $akhir = self::orderBy('kode_parameter', 'desc')->first();
            $lanjut = 1;

            if ($akhir) {
                $nomorTerakhir = (int)substr($akhir->kode_parameter, -3);
                $lanjut = $nomorTerakhir + 1;
            }

            $model->kode_parameter = 'PR-' . str_pad($lanjut, 3, '0', STR_PAD_LEFT);
        });
    }

    public function pengujian()
    {
        return $this->belongsToMany(Pengujian::class, 'parameter_pengujian', 'id_parameter', 'id_pengujian')
            ->withPivot(['nilai', 'keterangan'])
            ->withTimestamps();
    }

    public function form_pengajuan()
    {
        return $this->belongsToMany(FormPengajuan::class, 'parameter_pengajuan', 'id_parameter', 'id_pengajuan');
    }

    public function kategori()
    {
        return $this->belongsToMany(Kategori::class, 'parameter_kategori', 'id_parameter', 'id_kategori')
            ->withPivot('baku_mutu')
            ->withTimestamps();
    }

    public function subkategori()
    {
        return $this->belongsToMany(SubKategori::class, 'parameter_subkategori', 'id_parameter', 'id_subkategori')
                    ->withPivot('baku_mutu')
                    ->withTimestamps();    
    }
}
