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
        'id_user',
        'nilai',
        'keterangan',
        'status'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->status) {
                $model->status = 'draf';
            }
        });
    }

    public function hasil_uji()
    {
        return $this->belongsTo(HasilUji::class, 'id_hasil_uji');
    }

    public function parameter()
    {
        return $this->belongsTo(ParameterUji::class, 'id_parameter');
    }

    public function pengujian()
    {
        return $this->belongsTo(Pengujian::class, 'id_pengujian');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
