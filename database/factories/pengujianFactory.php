<?php

namespace Database\Factories;

use App\Models\FormPengajuan;
use App\Models\Pegawai;
use App\Models\Kategori;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pengujian>
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
        // Get or create verified pegawai
        $pegawai = Pegawai::where('status_verifikasi', 'diterima')
            ->inRandomOrder()
            ->first() ?? Pegawai::factory()->create([
                'status_verifikasi' => 'diterima'
            ]);

        // Get or create accepted form pengajuan
        $formPengajuan = FormPengajuan::where('status_pengajuan', 'diterima')
            ->inRandomOrder()
            ->first() ?? FormPengajuan::factory()->create([
                'status_pengajuan' => 'diterima'
            ]);

        // Use the kategori from form pengajuan
        $kategori = $formPengajuan->kategori;

        // Generate lab working hours (8 AM - 5 PM)
        $jamMulai = fake()->dateTimeBetween('08:00', '14:00')->format('H:i');
        $jamSelesai = Carbon::createFromFormat('H:i', $jamMulai)
            ->addHours(fake()->numberBetween(2, 4))
            ->format('H:i');

        return [
            'id_form_pengajuan' => $formPengajuan->id,
            'id_pegawai' => $pegawai->id,
            'id_kategori' => $kategori->id,
            'tanggal_uji' => fake()->dateTimeBetween('now', '+2 weeks')->format('Y-m-d'),
            'jam_mulai' => $jamMulai,
            'jam_selesai' => $jamSelesai,
            'status' => 'diproses'
        ];
    }

    public function selesai(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'selesai',
                'tanggal_uji' => fake()->dateTimeBetween('-1 week', 'now')->format('Y-m-d')
            ];
        });
    }
}
