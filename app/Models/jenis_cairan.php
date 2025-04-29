<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class jenis_cairan extends Model
{
    use HasFactory;

    protected $table = 'jenis_cairan';
    public $timestamps = false;
    
    protected $fillable = [
        'nama',
        'batas_minimum',
        'batas_maksimum'
    ];
}
