<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permissions extends SpatiePermission
{
    use HasFactory;

    protected $table = 'permissions';

    protected $guard_name = 'web';

    protected $fillable = [
        'name',
        'guard_name'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $akhir = self::max('id') ?? 0;
            $lanjut = $akhir + 1;

            $model->kode_permission = 'PS-' . str_pad($lanjut, 3, '0', STR_PAD_LEFT);
        });
    }
}
