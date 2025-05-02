<?php

namespace Database\Factories;

use App\Models\FormPengajuan;
use App\Models\Pegawai;
use Illuminate\Database\Eloquent\Factories\Factory;

class JadwalFactory extends Factory
{
    public function definition(): array
    {
        // Create or get a form pengajuan with 'diterima' status
        $formPengajuan = FormPengajuan::inRandomOrder()
            ->where('status_pengajuan', 'diterima')
            ->first() ?? FormPengajuan::factory()->create([
                'status_pengajuan' => 'diterima'
            ]);

        // Get or create a verified pegawai
        $pegawai = Pegawai::inRandomOrder()
            ->where('status_verifikasi', 'diterima')
            ->first() ?? Pegawai::factory()->create([
                'status_verifikasi' => 'diterima'
            ]);

        return [
            'id_form_pengajuan' => $formPengajuan->id,
            'id_pegawai' => $pegawai->id,
            'waktu_pengambilan' => fake()->dateTimeBetween('now', '+1 week')->format('Y-m-d'),
            'status' => fake()->randomElement(['diproses', 'selesai']),
            'keterangan' => fake()->optional()->sentence()
        ];
    }
}
