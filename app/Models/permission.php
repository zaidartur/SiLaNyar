<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class permission extends Model
{
    use HasFactory;

    protected $table = 'permission';

    protected $fillable = [
        'nama'
    ];

    public function role()
    {
        return $this->belongsToMany(role::class, 'permission_role');    
    }
}
