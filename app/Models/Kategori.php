<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';

    protected $fillable = [
        'nama',
        'harga'
    ];

    public function parameter()
    {
        return $this->belongsToMany(ParameterUji::class, 'parameter_kategori', 'id_kategori', 'id_parameter');
    }

    public function form_pengajuan()
    {
        return $this->belongsTo(FormPengajuan::class);
    }
}