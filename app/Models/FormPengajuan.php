<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormPengajuan extends Model
{
    use HasFactory;

    protected $table = 'form_pengajuan';

    protected $fillable = [
        'id_customer',
        'id_kategori',
        'id_jenis_cairan',
        'volume_sampel',
        'status_pengajuan',
        'metode_pengambilan',
        'lokasi'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model)
        {
            $prefix = 'DP-'.date('my');
            $akhir = self::where('kode_pengajuan', 'like', $prefix . '%')
                        ->orderBy('kode_pengajuan', 'desc')
                        ->first();

            $lanjut = 1;

            if($akhir)
            {
                $akhirKode = (int)substr($akhir->kode_pengajuan, -3);
                $lanjut = $akhirKode + 1;
            }

            $model->kode_pengajuan = $prefix.'-'.str_pad($lanjut, 3, '0', STR_PAD_LEFT);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');  
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');    
    }

    public function jenis_cairan()
    {
        return $this->belongsTo(JenisCairan::class, 'id_jenis_cairan');    
    }

    public function parameter()
    {
        return $this->belongsToMany(ParameterUji::class, 'parameter_pengajuan', 'id_pengajuan', 'id_parameter');
    }
}
