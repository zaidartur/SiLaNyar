<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasUuid;

class FormPengajuan extends Model
{
    use HasFactory, HasUuid;

    protected $table = 'form_pengajuan';

    protected $fillable = [
        'kode_pengajuan',
        'id_instansi',
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

            $model->kode_pengajuan = $prefix.str_pad($lanjut, 3, '0', STR_PAD_LEFT);

            // Pastikan status_pengajuan selalu memiliki nilai default
            if (!$model->status_pengajuan) {
                $model->status_pengajuan = 'proses_validasi';
            }
        });
    }

    // ini biar volume sampel nilainya selalu positif
    public function setVolumeSampelAttribute($value)
    {
        $this->attributes['volume_sampel'] = abs($value);
    }

    public function instansi()
    {
        return $this->belongsTo(Instansi::class, 'id_instansi', 'uuid');  
    }

    public function jadwal()
    {
        return $this->hasOne(Jadwal::class, 'id_form_pengajuan', 'uuid');    
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'uuid');    
    }

    public function jenis_cairan()
    {
        return $this->belongsTo(JenisCairan::class, 'id_jenis_cairan', 'uuid');    
    }

    public function parameter()
    {
        return $this->belongsToMany(ParameterUji::class, 'parameter_pengajuan', 'id_pengajuan', 'id_parameter', 'uuid', 'uuid');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_form_pengajuan', 'uuid');    
    }

    public function pengujian()
    {
        return $this->hasMany(Pengujian::class, 'id_form_pengajuan', 'uuid');    
    }
}
