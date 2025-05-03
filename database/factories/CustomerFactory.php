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
        $isInstansi = $jenisUser === 'instansi';
        
        return [
            'nama' => $isInstansi ? fake()->company() : fake()->name(),
            'jenis_user' => $jenisUser,
            'alamat_pribadi' => $isInstansi ? null : fake()->address(),
            'kontak_pribadi' => '+62' . fake()->numerify('8##########'),
            'nama_instansi' => $isInstansi ? fake()->randomElement([
                'PT. Air Mineral Indonesia',
                'PDAM Kota',
                'Dinas Lingkungan Hidup',
                'RS Umum Daerah',
                'PT. Industri Kimia',
                'Lab Kesehatan',
                'PT. Pengolahan Air'
            ]) : null,
            'tipe_instansi' => $isInstansi ? fake()->randomElement(['swasta', 'pemerintahan']) : null,
            'alamat_instansi' => $isInstansi ? fake()->address() : null,
            'kontak_instansi' => $isInstansi ? '(021) ' . fake()->numerify('#######') : null,
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password123'),
            'status_verifikasi' => 'diproses',
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
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
