<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $nama = fake()->name();
        return [
            'nama' => $nama,
            'nik' => fake()->unique()->numerify('################'),
            'tanggal_lahir' => fake()->date(),
            'rt' => fake()->numberBetween(1, 20),
            'rw' => fake()->numberBetween(1, 10),
            'kode_pos' => fake()->numberBetween(10000, 99999),
            'alamat' => fake()->address(),
            'username' => fake()->unique()->userName(),
            'no_telepon' => fake()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
        ];
    }
}
