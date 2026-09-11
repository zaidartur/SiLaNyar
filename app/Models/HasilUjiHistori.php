<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilUjiHistori extends Model
{
    use HasFactory, HasUuid;

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
        return $this->belongsTo(HasilUji::class, 'id_hasil_uji', 'uuid');
    }
}