<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission as SpatiePermission;

class permissions extends SpatiePermission
{
    use HasFactory;

    protected $table = 'permissions';

    protected $guard_name = 'pegawai';

    protected $fillable = [
        'name'
    ];
}
