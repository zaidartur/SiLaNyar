<?php

namespace Database\Factories;

use App\Models\HasilUji;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AduanFactory extends Factory
{
    public function definition(): array
    {
        return [
            'kode_aduan' => 'AU-' . str_pad(fake()->unique()->numberBetween(1, 999), 3, '0', STR_PAD_LEFT),
            'id_hasil_uji' => HasilUji::factory(),
            'id_user' => User::factory(),
            'masalah' => fake()->sentence(),
            'perbaikan' => fake()->paragraph(),
            'status' => fake()->randomElement(['diterima_administrasi', 'diterima_pengujian', 'ditolak', null]),
            'diverifikasi_oleh' => fake()->optional()->name()
        ];
    }
}
