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
        $jenisUser = fake()->randomElement(['instansi', 'perorangan']);
        
        return [
            'nama' => fake()->name(),
            'jenis_user' => $jenisUser,
            'alamat_pribadi' => $jenisUser === 'perorangan' ? fake()->address() : null,
            'kontak_pribadi' => fake()->phoneNumber(),
            'nama_instansi' => $jenisUser === 'instansi' ? fake()->company() : null,
            'tipe_instansi' => $jenisUser === 'instansi' ? fake()->randomElement(['swasta', 'pemerintahan']) : null,
            'alamat_instansi' => $jenisUser === 'instansi' ? fake()->address() : null,
            'kontak_instansi' => $jenisUser === 'instansi' ? fake()->phoneNumber() : null,
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'), // default password
            'status_verifikasi' => fake()->randomElement(['diproses', 'diterima', 'ditolak']),
            'email_verified_at' => fake()->optional()->dateTime(),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Configure the factory for an instansi customer.
     */
    public function instansi(): static
    {
        return $this->state(fn (array $attributes) => [
            'jenis_user' => 'instansi',
            'nama_instansi' => fake()->company(),
            'tipe_instansi' => fake()->randomElement(['swasta', 'pemerintahan']),
            'alamat_instansi' => fake()->address(),
            'kontak_instansi' => fake()->phoneNumber(),
        ]);
    }

    /**
     * Configure the factory for a perorangan customer.
     */
    public function perorangan(): static
    {
        return $this->state(fn (array $attributes) => [
            'jenis_user' => 'perorangan',
            'alamat_pribadi' => fake()->address(),
        ]);
    }

    /**
     * Configure the factory for a verified customer.
     */
    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'status_verifikasi' => 'diterima',
            'email_verified_at' => now(),
        ]);
    }
}
