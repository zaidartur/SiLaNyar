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
        'data_parameterdanpengujian',
        'status',
        'diupdate_oleh'
    ];

    protected $casts = ['data_parameterdanpengujian' => 'array'];

    public function hasil_uji()
    {
        return $this->belongsTo(HasilUji::class, 'id_hasil_uji');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}