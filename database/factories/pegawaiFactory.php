<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pegawai>
 */
class PegawaiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $jabatan = fake()->randomElement([
            'Admin Lab',
            'Kepala Lab',
            'Analis Kimia',
            'Analis Mikrobiologi',
            'Analis Air',
            'Manajer Teknis',
            'Staff Lab'
        ]);

        return [
            'nama' => fake()->name(),
            'jabatan' => $jabatan,
            'jenis_kelamin' => fake()->randomElement(['laki-laki', 'perempuan']),
            'status_verifikasi' => 'diproses',
            'no_telepon' => '+62' . fake()->numerify('8##########'),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ];
    }

    public function verified(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status_verifikasi' => 'diterima',
                'email_verified_at' => now(),
            ];
        });
    }
}
