<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $table = 'users';
    protected $guard_name = 'web';

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

    protected $casts = [
        'tanggal_lahir' => 'date',
        'rt' => 'integer',
        'rw' => 'integer',
        'kode_pos' => 'integer'
    ];

    public function form_pengajuan()
    {
        return $this->hasMany(FormPengajuan::class);
    }

    public function instansi()
    {
        return $this->hasMany(Instansi::class);
    }

    public function pengujian()
    {
        return $this->hasMany(Pengujian::class, 'id_user');
    }

    public function hasil_uji_histori()
    {
        return $this->hasMany(HasilUjiHistori::class, 'id_user');
    }

    public function aduan()
    {
        return $this->hasMany(Aduan::class, 'id_user');
    }
}
