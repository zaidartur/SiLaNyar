<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HasilUji extends Pivot
{
    use HasFactory;

    protected $table = 'hasil_uji';
    
    public $incrementing = true;

    protected $fillable = [
        'kode_hasil_uji',
        'id_parameter',
        'id_pengujian',
        'nilai',
        'keterangan',
        'status',
        'file_pdf',
        'diverifikasi_oleh',
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

            if ($akhir) {
                $akhirKode = (int)substr($akhir->kode_hasil_uji, -3);
                $lanjut = $akhirKode + 1;
            }

            $model->kode_hasil_uji = $prefix.'-'.str_pad($lanjut, 3, '0', STR_PAD_LEFT);

            // Pastikan status selalu memiliki nilai default
            if (!$model->status) {
                $model->status = 'draf';
            }
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
        return $this->hasMany(HasilUjiHistori::class, 'id_hasil_uji');
    }
}
