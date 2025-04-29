<?php

namespace Database\Factories;

use App\Models\pengujian;
use App\Models\form_pengajuan;
use App\Models\pegawai;
use App\Models\kategori;
use Illuminate\Database\Eloquent\Factories\Factory;

class pengujianFactory extends Factory
{
    protected $model = pengujian::class;

    public function definition(): array
    {
        $jamMulai = now();
        $jamSelesai = $jamMulai->copy()->addHour();
        
        return [
            'id_form_pengajuan' => form_pengajuan::factory(),
            'id_pegawai' => pegawai::factory(),
            'id_kategori' => kategori::factory(),
            'tanggal_uji' => $this->faker->date(),
            'jam_mulai' => $this->faker->time('H:i'),  // Menggunakan time() dengan format H:i
            'jam_selesai' => $this->faker->time('H:i'), // Menggunakan time() dengan format H:i
            'status' => $this->faker->randomElement(['diproses', 'selesai']),
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
