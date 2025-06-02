<?php

namespace Database\Factories;

use App\Models\FormPengajuan;
use App\Models\User;
use App\Models\Kategori;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class PengujianFactory extends Factory
{
    public function definition(): array
    {
        $formPengajuan = FormPengajuan::factory();
        $jamMulai = fake()->dateTimeBetween('08:00', '14:00')->format('H:i');
        $jamSelesai = Carbon::createFromFormat('H:i', $jamMulai)
            ->addHours(fake()->numberBetween(2, 4))
            ->format('H:i');
        static $counter = 1;

        return [
            'kode_pengujian' => 'DJ-' . str_pad($counter++, 3, '0', STR_PAD_LEFT),
            'id_form_pengajuan' => $formPengajuan,
            'id_user' => User::factory(),
            'id_kategori' => Kategori::factory(),
            'tanggal_uji' => fake()->dateTimeBetween('now', '+2 weeks')->format('Y-m-d'),
            'jam_mulai' => $jamMulai,
            'jam_selesai' => $jamSelesai,
            'status' => fake()->randomElement(['diproses', 'selesai'])
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
