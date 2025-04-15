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

    public function pengujian()
    {
        return $this->belongsToMany(pengujian::class, 'hasil_uji', 'id_parameter', 'id_pengujian')
                    ->withPivot('nilai', 'keterangan')
                    ->withTimeStamps();
    }

    public function hasil_uji()
    {
        return $this->hasMany(hasil_uji::class);    
    }
}
