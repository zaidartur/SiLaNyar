<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aduan extends Model
{
    use HasFactory;

    protected $table = 'aduan';

    protected $fillable = [
        'kode_aduan',
        'id_hasil_uji',
        'id_user',
        'masalah',
        'perbaikan',
        'status',
        'diverifikasi_oleh'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $prefix = 'AU-';
            $akhir = self::where('kode_aduan', 'like', $prefix . '%')
                        ->orderBy('kode_aduan', 'desc')
                        ->first();

            $lanjut = 1;

            if ($akhir) {
                $akhirKode = (int)substr($akhir->kode_aduan, -3);
                $lanjut = $akhirKode + 1;
            }

            $model->kode_aduan = $prefix . str_pad($lanjut, 3, '0', STR_PAD_LEFT);
        });
    }

    public function hasil_uji()
    {
        return $this->belongsTo(HasilUji::class, 'id_hasil_uji');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
