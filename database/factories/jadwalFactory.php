<?php

namespace Database\Factories;

use App\Models\FormPengajuan;
use App\Models\Pegawai;
use Illuminate\Database\Eloquent\Factories\Factory;

class JadwalFactory extends Factory
{
    public function definition(): array
    {
        $pegawai = Pegawai::factory()->create([
            'status_verifikasi' => 'diterima'
        ]);

        return [
            'id_form_pengajuan' => FormPengajuan::factory(),
            'id_pegawai' => $pegawai->id,
            'waktu_pengambilan' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'status' => $this->faker->randomElement(['diproses', 'selesai']),
            'keterangan' => $this->faker->sentence()
        ];
    }

    public function selesai(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'selesai'
        ]);
    }

    public function diproses(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'diproses'
        ]);
    }

    public function withFormPengajuan(FormPengajuan $formPengajuan): static
    {
        return $this->state(fn (array $attributes) => [
            'id_form_pengajuan' => $formPengajuan->id
        ]);
    }

    public function withPegawai(Pegawai $pegawai): static
    {
        return $this->state(fn (array $attributes) => [
            'id_pegawai' => $pegawai->id
        ]);
    }

    public function forDate(string $date): static
    {
        return $this->state(fn (array $attributes) => [
            'waktu_pengambilan' => $date
        ]);
    }
}
