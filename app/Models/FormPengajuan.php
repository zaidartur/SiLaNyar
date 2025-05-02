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

    public function customer()
    {
        return $this->belongsTo(customer::class, 'id_customer');  
    }

    public function pembayaran() 
    {
        return $this->hasOne(pembayaran::class);    
    }

    public function kategori()
    {
        return $this->belongsTo(kategori::class, 'id_kategori');    
    }

    public function parameter()
    {
        return $this->belongsToMany(parameter_uji::class, 'parameter_pengujian', 'id_parameter', 'id_pengujian');    
    }

    public function jenis_cairan()
    {
        return $this->belongsTo(jenis_cairan::class, 'id_jenis_cairan');    
    }
}
