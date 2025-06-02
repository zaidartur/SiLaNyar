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
            'kode_pengambilan' => 'JP-' . str_pad(fake()->unique()->numberBetween(1, 999), 3, '0', STR_PAD_LEFT),
            'id_form_pengajuan' => FormPengajuan::factory(),
            'id_user' => User::factory(),
            'waktu_pengambilan' => fake()->dateTimeBetween('now', '+1 week')->format('Y-m-d'),
            'status' => 'diproses',
            'keterangan' => fake()->optional()->sentence()
        ];
    }

    public function selesai()
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'selesai'
        ]);
    }
}
