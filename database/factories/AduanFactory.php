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
            'id_hasil_uji' => fn () => HasilUji::factory()->create()->uuid,
            'id_user' => fn () => User::factory()->create()->uuid,
            'terkait' => fake()->randomElement(['administrasi', 'pengujian']),
            'masalah' => fake()->sentence(8),
            'perbaikan' => fake()->sentence(15),
            'status' => fake()->randomElement(['diterima_administrasi', 'diterima_pengujian', 'ditolak']),
            'diverifikasi_oleh' => fake()->optional()->name(),
        ];
    }
}
