<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\permission;

class pegawai extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'pegawai';

    protected $fillable = [
        'nama',
        'jabatan',
        'jenis_kelamin',
        'no_telepon',
        'email',
        'password'
    ];

    protected $hidden = [
        'password'
    ];

    protected $casts = [
        'password' => 'hashed'
    ];

    public function role()
    {
        return $this->belongsToMany(role::class, 'has_role')
                    ->withPivot('status_verifikasi', 'diverifikasi_oleh', 'email_verified_at')
                    ->withTimestamps();
    }

    public function has_role()
    {
        
    }

    public function permission()
    {
        return $this->role()
                    ->withPivot('status_verifikasi', 'verifikasi')
                    ->with('permission')
                    ->get()
                    ->flatMap->permission
                    ->pluck('nama')
                    ->unique();    
    }
}
