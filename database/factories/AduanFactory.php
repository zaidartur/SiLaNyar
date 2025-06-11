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
            'id_hasil_uji' => HasilUji::factory(),
            'id_user' => User::factory(),
            'terkait' => fake()->randomElement(['administrasi', 'pengujian']),
            'masalah' => fake()->sentence(8),
            'perbaikan' => fake()->sentence(15),
            'status' => fake()->randomElement(['diterima_administrasi', 'diterima_pengujian', 'ditolak']),
            'diverifikasi_oleh' => fake()->optional()->name(),
        ];
    }
}
