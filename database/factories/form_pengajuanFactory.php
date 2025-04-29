<?php

namespace Database\Factories;

use App\Models\form_pengajuan;
use App\Models\customer;
use App\Models\kategori;
use App\Models\jenis_cairan;
use App\Models\pembayaran;
use Illuminate\Database\Eloquent\Factories\Factory;

class form_pengajuanFactory extends Factory
{
    protected $model = form_pengajuan::class;

    public function definition(): array
    {
        return [
            'id_customer' => customer::factory(),
            'id_pembayaran' => pembayaran::factory(),
            'id_kategori' => kategori::factory(),
            'id_jenis_cairan' => jenis_cairan::factory(),
            'volume_sampel' => $this->faker->randomFloat(2, 1, 1000),
            'status_pengajuan' => $this->faker->randomElement(['proses_validasi', 'diterima', 'ditolak']),
            'metode_pengambilan' => $this->faker->randomElement(['diantar', 'diambil']),
            'lokasi' => $this->faker->address(),
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
