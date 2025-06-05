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
        'desa_kelurahan',
        'email',
        'no_telepon',
        'posisi_jabatan',
        'departemen_divisi',
        'surat_keterangan_penugasan',
        'foto_kartu_identitas',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Perbaiki logika pembuatan kode instansi
            $akhir = self::orderBy('kode_instansi', 'desc')->first();
            $lanjut = 1;

            if ($akhir) {
                $nomorTerakhir = (int)substr($akhir->kode_instansi, -3);
                $lanjut = $nomorTerakhir + 1;
            }

            $model->kode_instansi = 'IN-' . str_pad($lanjut, 3, '0', STR_PAD_LEFT);
        });
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function form_pengajuan()
    {
        return $this->hasMany(FormPengajuan::class, 'id_instansi');
    }
}
