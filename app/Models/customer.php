<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class customer extends Model
{
    use HasFactory;

    protected $table = 'customer';
    
    protected $fillable = [
        'nama',
        'jenis_user',
        'alamat_pribade',
        'kontak_pribadi',
        'nama_instansi',
        'tipe_instansi',
        'alamat_instansi',
        'kontak_instansi'
    ];

    public function form_pengajuan()
    {
        return $this->hasMany(form_pengajuan::class);    
    }
}
