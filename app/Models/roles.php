<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as SpatieRole;

class roles extends SpatieRole
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = [
        'name',
        'guard_name'  // Tambahkan guard_name ke fillable
    ];

    // Set default guard name
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        
        $this->guard_name = $this->guard_name ?? 'pegawai';
    }
}
