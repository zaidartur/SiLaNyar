<?php

namespace Database\Factories;

use App\Models\Instansi;
use App\Models\Kategori;
use App\Models\JenisCairan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FormPengajuan>
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
        $metode = fake()->randomElement(['diantar', 'diambil']);
        
        return [
            'id_instansi' => Instansi::factory(),
            'id_kategori' => fake()->boolean(80) ? Kategori::factory() : null,
            'id_jenis_cairan' => JenisCairan::factory(),
            'volume_sampel' => fake()->randomFloat(2, 0.1, 100),
            'status_pengajuan' => fake()->randomElement(['proses_validasi', 'diterima', 'ditolak']),
            'metode_pengambilan' => $metode,
            'lokasi' => $metode === 'diambil' ? fake()->address() : null,
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
     * Configure the factory for a submission in process of validation.
     */
    public function prosesValidasi(): static
    {
        return $this->state(fn (array $attributes) => [
            'status_pengajuan' => 'proses_validasi',
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
