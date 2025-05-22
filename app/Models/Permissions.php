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
        'kode_permission',
        'name',
        'guard_name'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->kode_permission) {
                $akhir = self::orderBy('kode_permission', 'desc')->first();
                $lanjut = 1;

                if ($akhir) {
                    $nomorTerakhir = (int)substr($akhir->kode_permission, -3);
                    $lanjut = $nomorTerakhir + 1;
                }

                $model->kode_permission = 'PS-' . str_pad($lanjut, 3, '0', STR_PAD_LEFT);
            }
        });
    }
}
