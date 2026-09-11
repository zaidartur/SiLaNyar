<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Concerns\HasUuid;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, HasUuid;

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

    public function instansi()
    {
        return $this->hasMany(Instansi::class, 'id_user', 'uuid');
    }

    public function pengujian()
    {
        return $this->hasMany(Pengujian::class, 'id_user', 'uuid');
    }

    public function hasil_uji_histori()
    {
        return $this->hasMany(HasilUjiHistori::class, 'id_user', 'uuid');
    }

    public function aduan()
    {
        return $this->hasMany(Aduan::class, 'id_user', 'uuid');
    }

    public function getDefaultGuardName()
    {
        return $this->guard_name;
    }
}
