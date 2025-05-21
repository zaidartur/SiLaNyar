<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwal';

    protected $fillable = [
        'kode_pengambilan',
        'id_form_pengajuan',
        'id_user',
        'waktu_pengambilan',
        'status',
        'keterangan'
    ];

    protected $casts = [
        'waktu_pengambilan' => 'date'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $akhir = self::max('id') ?? 0;
            $lanjut = $akhir + 1;

            $model->kode_pengambilan = 'JP-' . str_pad($lanjut, 3, '0', STR_PAD_LEFT);
        });
    }

    public function form_pengajuan()
    {
        return $this->belongsTo(FormPengajuan::class, 'id_form_pengajuan');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}