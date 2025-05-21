<?php

namespace Database\Factories;

use App\Models\FormPengajuan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class JadwalFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_form_pengajuan' => FormPengajuan::factory(),
            'id_user' => User::factory(),
            'waktu_pengambilan' => fake()->dateTimeBetween('now', '+1 week')->format('Y-m-d'),
            'status' => fake()->randomElement(['diproses', 'selesai']),
            'keterangan' => fake()->optional()->sentence()
        ];
    }
}
