<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instansi extends Model
{
    use HasFactory;

    protected $table = 'instansi';

    protected $fillable = [
        'id_customer',
        'nama',
        'tipe',
        'alamat',
        'no_telepon',
        'email',
    ];

    public function customer()
    {
        $this->belongsTo(Customer::class, 'id_customer');
    }
}
