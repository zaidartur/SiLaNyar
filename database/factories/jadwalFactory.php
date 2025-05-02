<?php

namespace Database\Factories;

use App\Models\form_pengajuan;
use App\Models\pegawai;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\jadwal>
 */
class JadwalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_form_pengajuan' => form_pengajuan::factory(),
            'id_pegawai' => pegawai::factory(),
            'waktu_pengambilan' => fake()->date(),
            'status' => fake()->randomElement(['diproses', 'selesai']),
            'keterangan' => fake()->sentence(),
        ];
    }

    /**
     * Configure the factory for a completed schedule.
     */
    public function selesai(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'selesai',
        ]);
    }

    /**
     * Configure the factory for a processing schedule.
     */
    public function diproses(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'diproses',
        ]);
    }

    /**
     * Configure the factory with a specific date.
     */
    public function forDate(string $date): static
    {
        return $this->state(fn (array $attributes) => [
            'waktu_pengambilan' => $date,
        ]);
    }
}
