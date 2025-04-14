<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class form_pengajuan extends Model
{
    use HasFactory;

    protected $table = 'form_pengajuan';

    protected $fillable = [
        'id_pembayaran',
        'id_kategori',
        'deskripsi',
        'status_pengajuan',
        'tanggal_terima',
        'metode_pengambilan'
    ];

    public function pembayaran() 
    {
        return $this()->belongsTo(pembayaran::class, 'id_pembayaran');    
    }

    public function kategori()
    {
        return $this()->belongsTo(kategori::class, 'id_kategori');    
    }
}
