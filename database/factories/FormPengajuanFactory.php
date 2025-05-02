<?php

namespace Database\Factories;

use App\Models\customer;
use App\Models\kategori;
use App\Models\jenis_cairan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\form_pengajuan>
 */
class FormPengajuanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_customer' => customer::factory(),
            'id_kategori' => kategori::factory(),
            'id_jenis_cairan' => jenis_cairan::factory(),
            'volume_sampel' => fake()->randomFloat(2, 0.1, 100),
            'status_pengajuan' => fake()->randomElement(['proses_validasi', 'diterima', 'ditolak']),
            'metode_pengambilan' => fake()->randomElement(['diantar', 'diambil']),
            'lokasi' => fake()->when(
                fn ($attrs) => $attrs['metode_pengambilan'] === 'diambil',
                fn () => fake()->address(),
                fn () => null
            ),
        ];
    }

    /**
     * Configure the factory for a diantar submission.
     */
    public function diantar(): static
    {
        return $this->state(fn (array $attributes) => [
            'metode_pengambilan' => 'diantar',
            'lokasi' => null,
        ]);
    }

    /**
     * Configure the factory for a diambil submission.
     */
    public function diambil(): static
    {
        return $this->state(fn (array $attributes) => [
            'metode_pengambilan' => 'diambil',
            'lokasi' => fake()->address(),
        ]);
    }

    /**
     * Configure the factory for an accepted submission.
     */
    public function diterima(): static
    {
        return $this->state(fn (array $attributes) => [
            'status_pengajuan' => 'diterima',
        ]);
    }

    /**
     * Configure the factory for a rejected submission.
     */
    public function ditolak(): static
    {
        return $this->state(fn (array $attributes) => [
            'status_pengajuan' => 'ditolak',
        ]);
    }
}
