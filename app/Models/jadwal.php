<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwal';

    protected $fillable = [
        'id_form_pengajuan',
        'id_pegawai',
        'waktu_pengambilan',
        'lokasi',
        'status',
        'keterangan'
    ];

    public function form_pengajuan()
    {
        return $this->belongsTo(form_pengajuan::class, 'id_form_pengajuan');    
    }

    public function pegawai()
    {
        return $this->belongsTo(pegawai::class, 'id_pegawai');
    }
}
