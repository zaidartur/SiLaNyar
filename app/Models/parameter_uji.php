<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class parameter_uji extends Model
{
    use HasFactory;

    protected $table = 'parameter_uji';

    protected $fillable = [
        'nama_parameter',
        'satuan',
        'baku_mutu',
        'biaya'
    ];
}
