<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\pegawai>
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
        return [
            'nama' => fake()->name(),
            'jabatan' => fake()->randomElement(['Admin', 'Kepala Lab', 'Analis', 'Manajer Teknis']),
            'jenis_kelamin' => fake()->randomElement(['laki-laki', 'perempuan']),
            'status_verifikasi' => fake()->randomElement(['diproses', 'diterima', 'ditolak']),
            'no_telepon' => fake()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'), // default password
            'email_verified_at' => fake()->optional()->dateTime(),
        ];
    }

    /**
     * Configure the factory for a verified pegawai.
     */
    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'status_verifikasi' => 'diterima',
            'email_verified_at' => now(),
        ]);
    }

    /**
     * Configure the factory for an admin role.
     */
    public function asAdmin(): static
    {
        return $this->state(fn (array $attributes) => [
            'jabatan' => 'Admin',
        ]);
    }

    /**
     * Configure the factory for a kepala lab role.
     */
    public function asKepalaLab(): static
    {
        return $this->state(fn (array $attributes) => [
            'jabatan' => 'Kepala Lab',
        ]);
    }

    /**
     * Configure the factory for an analis role.
     */
    public function asAnalis(): static
    {
        return $this->state(fn (array $attributes) => [
            'jabatan' => 'Analis',
        ]);
    }

    /**
     * Configure the factory for a manajer teknis role.
     */
    public function asManajerTeknis(): static
    {
        return $this->state(fn (array $attributes) => [
            'jabatan' => 'Manajer Teknis',
        ]);
    }
}
