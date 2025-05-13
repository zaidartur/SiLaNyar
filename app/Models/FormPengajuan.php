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
            $akhir = self::where('kode_hasil_uji', 'like', $prefix . '%')
                        ->orderBy('kode_hasil_uji', 'desc')
                        ->first();

            $lanjut = 1;

            if($akhir)
            {
                $akhirKode = (int)substr($akhir->kode_hasil_uji, -3);
                $lanjut = $akhirKode + 1;
            }

            $model->kode_hasil_uji = $prefix.'-'.str_pad($lanjut, 3, '0', STR_PAD_LEFT);
        });
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer');  
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
