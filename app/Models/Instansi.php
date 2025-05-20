<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instansi extends Model
{
    use HasFactory;

    protected $table = 'instansi';

    protected $fillable = [
        'id_user',
        'nama',
        'tipe',
        'alamat',
        'wilayah',
        'desa/kelurahan',
        'email',
        'no_telepon',
        'posisi/jabatan',
        'departemen/divisi',
        'status_verifikasi',
        'diverifikasi_oleh'
    ];
    
    public function user()
    {
        $this->belongsTo(User::class, 'id_user');
    }
}
