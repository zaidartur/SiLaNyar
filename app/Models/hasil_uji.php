<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class hasil_uji extends Model
{
    use HasFactory;

    protected $table = 'hasil_uji';

    protected $fillable = [
        'id_parameter',
        'id_pengujian',
        'nilai',
        'keterangan'
    ];

    public function parameter()
    {
        return $this->belongsTo(parameter_uji::class, 'id_parameter');
    }

    public function pengujian()
    {
        return $this->belongsTo(pengujian::class, 'id_pengujian');
    }
}
