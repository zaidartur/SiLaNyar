<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as SpatieRole;

class Roles extends SpatieRole
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = [
        'kode_role',
        'name',
        'guard_name',
        'dashboard_view',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->kode_role) {
                $akhir = self::max('id') ?? 0;
                $lanjut = $akhir + 1;
                $model->kode_role = 'RL-' . str_pad($lanjut, 3, '0', STR_PAD_LEFT);
            }
        });
    }

    // Set default guard name
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->guard_name = $this->guard_name ?? 'web';
    }
}
