<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubKategori extends Model
{
    use HasFactory;

    protected $table = 'subkategori';

    protected $fillable = [
        'nama'
    ];

    protected static function boot()
    {
        
    }

    public function parameter()
    {
        return $this->belongsToMany(ParameterUji::class, 'parameter_subkategori', 'id_subkategori', 'id_parameter')
                    ->withPivot('baku_mutu')
                    ->withTimestamps();
    }

    public function kategori()
    {
        return $this->hasMany(Kategori::class);    
    }
}
