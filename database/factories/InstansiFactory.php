<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InstansiFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_user' => User::factory(),
            'nama' => fake()->company(),
            'tipe' => fake()->randomElement(['swasta', 'pemerintahan', 'pribadi']),
            'alamat' => fake()->address(),
            'wilayah' => fake()->city(),
            'desa/kelurahan' => fake()->city(),
            'email' => fake()->unique()->companyEmail(),
            'no_telepon' => fake()->phoneNumber(),
            'posisi/jabatan' => fake()->jobTitle(),
            'departemen/divisi' => fake()->word(),
            'status_verifikasi' => fake()->randomElement(['diproses', 'diterima', 'ditolak']),
            'diverifikasi_oleh' => fake()->name()
        ];
    }
}
