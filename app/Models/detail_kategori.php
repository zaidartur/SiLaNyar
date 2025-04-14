<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class detail_kategori extends Model
{
    use HasFactory;

    protected $table = 'detail_kategori';

    protected $fillable = [
        'id_parameter',
        'id_kategori',
        'keterangan'
    ];

    public function parameter()
    {
        return $this()->belongsTo(parameter_uji::class, 'id_parameter');    
    }

    public function kategori()
    {
        return $this()->belongsTo(kategori::class, 'id_kategori');    
    }
}
