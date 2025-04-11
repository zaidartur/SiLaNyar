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
        'tanggal_mulai',
        'tanggal_selesai',
        'status'
    ];

    public function form_pengajuan()
    {
        return $this()->belongsTo(form_pengajuan::class, 'id_form_pengajuan');    
    }

    public function pegawai()
    {
        return $this()->belongsTo(pegawai::class, 'id_pegawai');    
    }

    public function kategori()
    {
        return $this()->belongsTo(kategori::class, 'id_kategori');    
    }
}
