<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwal';

    protected $fillable = [
        'id_form_pengajuan',
        'id_pegawai',
        'waktu_pengambilan',
        'status',
        'keterangan'
    ];

    protected static function boot()
    {
        parent::boot();    
    }

    public function form_pengajuan()
    {
        return $this->belongsTo(FormPengajuan::class, 'id_form_pengajuan');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_pegawai');
    }
}