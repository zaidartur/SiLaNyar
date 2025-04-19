<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class role extends Model
{
    use HasFactory;

    protected $table = 'role';

    protected $fillable = [
        'nama'
    ];

    public function permission()
    {
        return $this->belongsToMany(permission::class, 'permission_role');   
    }

    public function pegawai()
    {
        return $this->belongsToMany(pegawai::class, 'has_role');
    }
}
