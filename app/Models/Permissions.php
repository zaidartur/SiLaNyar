<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permissions extends SpatiePermission
{
    use HasFactory;

    protected $table = 'permissions';

    protected $guard_name = 'pegawai';

    protected $fillable = [
        'name',
        'guard_name'  // Tambahkan guard_name ke fillable (ini biar guad_name bisa diisi di set up test, soalnya kalau nggak nanti harus setting default guad_name di migrations)
    ];
}
