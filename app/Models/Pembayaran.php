<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';

    protected $fillable = [
        'id_order',
        'id_form_pengajuan',
        'total_biaya',
        'tanggal_pembayaran',
        'metode_pembayaran',
        'status_pembayaran',
        'bukti_pembayaran',
        'id_transaksi'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($pembayaran) {
            if ($pembayaran->total_biaya < 0) {
                throw new QueryException(
                    'mysql', // connection
                    'Total biaya tidak boleh negatif', // sql
                    ['total_biaya' => $pembayaran->total_biaya], // bindings
                    new \Exception('Total biaya harus positif') // previous exception
                );
            }
        });
    }

    public function form_pengajuan()
    {
        return $this->belongsTo(FormPengajuan::class, 'id_form_pengajuan', 'id');    
    }
}
