<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'customer';
    
    protected $fillable = [
        'nama',
        'nik',
        'tanggal_lahir',
        'rt',
        'rw',
        'kode_pos',
        'alamat',
        'username',
        'no_telepon',
        'email',
    ];

    public function form_pengajuan()
    {
        return $this->hasMany(FormPengajuan::class);
    }

    public function instansi()
    {
        return $this->hasMany(Instansi::class);    
    }
}
