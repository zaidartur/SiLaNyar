<?php

namespace Database\Factories;

use App\Models\jadwal;
use App\Models\form_pengajuan;
use App\Models\pegawai;
use Illuminate\Database\Eloquent\Factories\Factory;

class jadwalFactory extends Factory
{
    protected $model = jadwal::class;

    public function definition(): array
    {
        return [
            'id_form_pengajuan' => form_pengajuan::factory(),
            'id_pegawai' => pegawai::factory(),
            'waktu_pengambilan' => $this->faker->date(), // Changed to match date column type
            'status' => $this->faker->randomElement(['diproses', 'selesai']),
            'keterangan' => $this->faker->sentence(),
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
