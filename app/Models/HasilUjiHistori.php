<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilUjiHistori extends Model
{
    use HasFactory;

    protected $table = 'hasil_uji_histori';

    protected $fillable = [
        'id_hasil_uji',
        'id_parameter',
        'id_pengujian',
        'id_pegawai',
        'nilai',
        'keterangan',
        'status'
    ];

    public function hasil_uji()
    {
        return $this->belongsTo(HasilUji::class, 'id_hasil_uji');    
    }
}
