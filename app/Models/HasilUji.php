<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilUji extends Model
{
    use HasFactory;

    protected $table = 'hasil_uji';

    public $incrementing = true;

    public $timestamps = true;

    protected $fillable = [
        'kode_hasil_uji',
        'id_pengujian',
        'status',
        'file_pdf',
        'diupdate_oleh',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $prefix = 'HU-' . date('my');
            $akhir = self::where('kode_hasil_uji', 'like', $prefix . '%')
                ->orderBy('kode_hasil_uji', 'desc')
                ->first();

            $lanjut = 1;

            if ($akhir) {
                $akhirKode = (int)substr($akhir->kode_hasil_uji, -3);
                $lanjut = $akhirKode + 1;
            }

            $model->kode_hasil_uji = $prefix . '-' . str_pad($lanjut, 3, '0', STR_PAD_LEFT);

            // Hanya set status default jika belum diset
            if ($model->status === null) {
                $model->status = 'draf';
            }
        });

        // Validasi transisi status
        // Hanya mengizinkan transisi status yang valid (iki aku yo jik sek nambahi, nek marai error hapusen, tapi kudune ora)
        static::saving(function ($model) {
            $validTransitions = [
                'draf' => ['revisi', 'proses_review'],
                'revisi' => ['proses_review', 'draf'],
                'proses_review' => ['revisi', 'proses_peresmian'],
                'proses_peresmian' => ['revisi', 'selesai'],
                'selesai' => []
            ];

            if ($model->isDirty('status')) {
                $oldStatus = $model->getOriginal('status') ?? 'draf';
                $newStatus = $model->status;

                // Jika status baru tidak valid untuk status saat ini, kembalikan ke status sebelumnya
                if (!in_array($newStatus, $validTransitions[$oldStatus] ?? [])) {
                    $model->status = $oldStatus;
                }

                // Set proses_review_at ketika status berubah ke proses_review
                if ($model->status === 'proses_review' && $oldStatus !== 'proses_review') {
                    $model->proses_review_at = now();
                }
            }
        });
    }

    public function pengujian()
    {
        return $this->belongsTo(Pengujian::class, 'id_pengujian');
    }

    public function riwayat()
    {
        return $this->hasMany(HasilUjiHistori::class, 'id_hasil_uji');
    }

    public function aduan()
    {
        return $this->hasOne(Aduan::class, 'id_hasil_uji');
    }

    public function getStatusAduan()
    {
        return $this->aduan?->status ?? $this->status;
    }
}
