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
            $akhir = self::max('id') ?? 0;
            $lanjut = $akhir + 1;

            $model->kode_parameter = 'PR-' . str_pad($lanjut, 3, '0', STR_PAD_LEFT);
        });
    }

    public function pengujian()
    {
        return $this->belongsToMany(pengujian::class, 'hasil_uji', 'id_parameter', 'id_pengujian')
            ->withPivot('nilai', 'keterangan')
            ->withTimeStamps();
    }

    public function hasil_uji()
    {
        return $this->hasMany(HasilUji::class);
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
}
