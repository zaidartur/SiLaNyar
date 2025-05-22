<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aduan extends Model
{
    use HasFactory;

    protected $table = 'aduan';

    protected $fillable = [
        'kode_aduan',
        'id_hasil_uji',
        'id_user',
        'masalah',
        'perbaikan',
        'status'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $akhir = self::max('id') ?? 0;
            $lanjut = $akhir + 1;

            $model->kode_aduan = 'AU-' . str_pad($lanjut, 3, '0', STR_PAD_LEFT);
        });
    }

    public function hasil_uji()
    {
        return $this->belongsTo(HasilUji::class, 'id_hasil_uji');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');    
    }
}
