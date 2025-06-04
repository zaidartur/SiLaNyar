<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class Pengujian extends Model
{
    use HasFactory;

    protected $table = 'pengujian';

    protected $fillable = [
        'kode_pengujian',
        'id_form_pengajuan',
        'id_user',
        'id_kategori',
        'tanggal_uji',
        'jam_mulai',
        'jam_selesai',
        'status'
    ];

    protected $casts = [
        'tanggal_uji' => 'date',
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
    ];

    protected static function boot()
    {  
        parent::boot();

        static::creating(function ($model)
        {
            $akhir = self::orderBy('kode_pengujian', 'desc')->first();
            $lanjut = 1;

            if ($akhir) {
                $nomorTerakhir = (int)substr($akhir->kode_pengujian, -3);
                $lanjut = $nomorTerakhir + 1;
            }
            
            $model->kode_pengujian = 'DJ-'.str_pad($lanjut, 3, '0', STR_PAD_LEFT);
        });
    }

    public function form_pengajuan()
    {
        return $this->belongsTo(FormPengajuan::class, 'id_form_pengajuan');    
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');    
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    public function parameter_uji()
    {
        return $this->belongsToMany(ParameterUji::class, 'parameter_pengujian', 'id_pengujian', 'id_parameter')
                    ->withPivot(['nilai', 'keterangan'])
                    ->withTimestamps();
    }

    public function hasil_uji()
    {
        return $this->belongsTo(HasilUji::class, 'id_hasil_uji');
    }
}
