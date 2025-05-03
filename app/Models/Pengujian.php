<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class Pengujian extends Model
{
    use HasFactory;

    protected $table = 'pengujian';

    protected $fillable = [
        'id_form_pengajuan',
        'id_pegawai',
        'id_kategori',
        'tanggal_uji',
        'jam_mulai',
        'jam_selesai',
        'status'
    ];

    public function form_pengajuan()
    {
        return $this->belongsTo(FormPengajuan::class, 'id_form_pengajuan');    
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai');    
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    public function parameter_uji()
    {
        return $this->belongsToMany(ParameterUji::class, 'hasil_uji', 'id_pengujian', 'id_parameter')
                    ->withPivot('nilai', 'keterangan')
                    ->withTimestamps();
    }

    public function hasil_uji()
    {
        return $this->hasMany(HasilUji::class);
    }

    public function update(array $attributes = [], array $options = [])
    {
        // Validasi jam selesai harus setelah jam mulai
        if (isset($attributes['jam_mulai']) && isset($attributes['jam_selesai'])) {
            $validator = Validator::make($attributes, [
                'jam_selesai' => 'after:jam_mulai'
            ]);

            if ($validator->fails()) {
                throw ValidationException::withMessages([
                    'jam_selesai' => ['Jam selesai harus setelah jam mulai']
                ]);
            }
        }

        // Jika status saat ini adalah 'selesai', hapus atribut status dari update
        if ($this->status === 'selesai' && isset($attributes['status'])) {
            unset($attributes['status']);
        }
        
        return parent::update($attributes, $options);
    }
}
