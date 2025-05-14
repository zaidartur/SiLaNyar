<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Pegawai extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $table = 'pegawai';

    protected $fillable = [
        'nama',
        'jabatan',
        'jenis_kelamin',
        'no_telepon',
        'email',
        'password',
        'status_verifikasi'
    ];

    protected $hidden = [
        'password'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed'
    ];

    protected $guard_name = 'pegawai';
}
