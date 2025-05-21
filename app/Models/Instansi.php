<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instansi extends Model
{
    use HasFactory;

    protected $table = 'instansi';

    protected $fillable = [
        'kode_instansi',
        'id_user',
        'nama',
        'tipe',
        'alamat',
        'wilayah',
        'desa/kelurahan',
        'email',
        'no_telepon',
        'posisi/jabatan',
        'departemen/divisi',
        'status_verifikasi',
        'diverifikasi_oleh'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $akhir = self::max('id') ?? 0;
            $lanjut = $akhir + 1;

            $model->kode_instansi = 'IN-' . str_pad($lanjut, 3, '0', STR_PAD_LEFT);
        });
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
