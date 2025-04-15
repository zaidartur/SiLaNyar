<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';

    protected $fillable = [
        'id_pembayaran',
        'total_biaya',
        'tanggal_pembayaran',
        'metode_pembayaran',
        'status_pembayaran',
        'bukti_pembayaran'
    ];

    public function form_pengajuan()
    {
        return $this->belongsTo(form_pengajuan::class);    
    }
}
