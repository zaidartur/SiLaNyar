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

    public function update(array $attributes = [], array $options = [])
    {
        // Jika status saat ini adalah 'selesai', hapus status dari atribut yang akan diupdate
        if ($this->status === 'selesai' && isset($attributes['status'])) {
            unset($attributes['status']);
        }
        
        return parent::update($attributes, $options);
    }

    public function form_pengajuan()
    {
        return $this->belongsTo(FormPengajuan::class, 'id_form_pengajuan');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }
}