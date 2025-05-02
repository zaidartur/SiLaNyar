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
}
