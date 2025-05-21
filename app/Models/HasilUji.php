<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilUji extends Model
{
    use HasFactory;

    protected $table = 'hasil_uji';

    protected $fillable = [
        'kode_hasil_uji',
        'id_parameter',
        'id_pengujian',
        'nilai',
        'keterangan',
        'status',
        'file_pdf'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $prefix = 'HU-' . date('my');
            $akhir = self::where('kode_hasil_uji', 'like', $prefix . '%')
                ->orderBy('kode_hasil_uji', 'desc')
                ->first();

            $lanjut = 1;

            if ($akhir)
            {
                $akhirKode = (int)substr($akhir->kode_hasil_uji, -3);
                $lanjut = $akhirKode + 1;
            }

            $model->kode_hasil_uji = $prefix.'-'.str_pad($lanjut, 3, '0', STR_PAD_LEFT);
        });
    }


    public function parameter()
    {
        return $this->belongsTo(ParameterUji::class, 'id_parameter');
    }

    public function pengujian()
    {
        return $this->belongsTo(Pengujian::class, 'id_pengujian');
    }

    public function riwayat()
    {
        return $this->hasMany(HasilUjiHistori::class);
    }
}
