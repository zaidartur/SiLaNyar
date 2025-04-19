<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => $this->faker->name(),
            'jenis_user' => $this->faker->randomElement(['instansi', 'perorangan']),
            'alamat_pribadi' => $this->faker->address(),
            'kontak_pribadi' => $this->faker->phoneNumber(),
            'nama_instansi' => $this->faker->optional()->company(),
            'tipe_instansi' => $this->faker->optional()->randomElement(['swasta', 'pemerintahan']),
            'alamat_instansi' => $this->faker->optional()->address(),
            'kontak_instansi' => $this->faker->optional()->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'), // password default
            'status_verifikasi' => 'diterima', // agar bisa login (jika ada pengecekan)
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ];
    }
}
