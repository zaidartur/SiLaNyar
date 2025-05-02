<?php

namespace Database\Factories;

use App\Models\FormPengajuan;
use App\Models\Pegawai;
use App\Models\Kategori;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\pengujian>
 */
class PengujianFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $jamMulai = fake()->time('H:i');
        return [
            'id_form_pengajuan' => FormPengajuan::factory(),
            'id_pegawai' => Pegawai::factory(),
            'id_kategori' => Kategori::factory(),
            'tanggal_uji' => fake()->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'jam_mulai' => $jamMulai,
            'jam_selesai' => fake()->dateTimeInInterval($jamMulai, '+4 hours')->format('H:i'),
            'status' => fake()->randomElement(['diproses', 'selesai']),
        ];
    }

    /**
     * Configure the factory for a completed test.
     */
    public function selesai(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'selesai',
            'tanggal_uji' => fake()->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
        ]);
    }

    /**
     * Configure the factory for an ongoing test.
     */
    public function diproses(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'diproses',
            'tanggal_uji' => fake()->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
        ]);
    }

    /**
     * Configure the factory for today's test.
     */
    public function hariIni(): static
    {
        return $this->state(fn (array $attributes) => [
            'tanggal_uji' => now()->format('Y-m-d'),
        ]);
    }

    /**
     * Configure the factory with specific testing duration.
     */
    public function durasi(int $jamDurasi = 2): static
    {
        $jamMulai = fake()->time('H:i');
        return $this->state(fn (array $attributes) => [
            'jam_mulai' => $jamMulai,
            'jam_selesai' => fake()->dateTimeInInterval($jamMulai, "+{$jamDurasi} hours")->format('H:i'),
        ]);
    }
}
