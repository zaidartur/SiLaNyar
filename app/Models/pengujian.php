<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pengujian extends Model
{
    use HasFactory;

    protected $table = 'pengujian';

    protected $fillable = [
        'id_form_pengajuan',
        'id_pegawai',
        'id_kategori',
        'tanggal_uji',
        'jam_mulai',
        'jam_selesai',
        'status'
    ];

    public function form_pengajuan()
    {
        return $this->belongsTo(form_pengajuan::class, 'id_form_pengajuan');    
    }

    public function pegawai()
    {
        return $this->belongsTo(pegawai::class, 'id_pegawai');    
    }

    public function kategori()
    {
        return $this->belongsTo(kategori::class, 'id_kategori');
    }

    public function parameter_uji()
    {
        return $this->belongsToMany(parameter_uji::class, 'hasil_uji', 'id_pengujian', 'id_parameter')
                    ->withPivot('nilai', 'keterangan')
                    ->withTimestamps();
    }
    public function hasil_uji()
    {
        return $this->hasMany(hasil_uji::class);
    }
}
