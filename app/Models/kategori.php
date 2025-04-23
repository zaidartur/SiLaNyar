<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';

    protected $fillable = [
        'nama',
        'harga'
    ];

    public function parameter()
    {
        return $this->belongsToMany(parameter_uji::class, 'parameter_kategori', 'id_parameter', 'id_kategori');
    }

    public function form_pengajuan()
    {
        return $this->belongsTo(form_pengajuan::class);
    }
}