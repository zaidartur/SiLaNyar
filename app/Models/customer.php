<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class customer extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'customer';
    
    protected $fillable = [
        'nama',
        'jenis_user',
        'alamat_pribadi',
        'kontak_pribadi',
        'nama_instansi',
        'tipe_instansi',
        'alamat_instansi',
        'kontak_instansi',
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed'
    ];

    public function form_pengajuan()
    {
        return $this->hasMany(form_pengajuan::class);    
    }
}
