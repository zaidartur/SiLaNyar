<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilUji extends Model
{
    use HasFactory;

    protected $table = 'hasil_uji';

    protected $fillable = [
        'id_parameter',
        'id_pengujian',
        'nilai',
        'keterangan',
        'status'
    ];

    
    public function parameter()
    {
        return $this->belongsTo(ParameterUji::class, 'id_parameter');
    }

    public function pengujian()
    {
        return $this->belongsTo(Pengujian::class, 'id_pengujian');
    }

    public function riwayat()
    {
        return $this->hasMany(HasilUjiHistori::class);    
    }
}
