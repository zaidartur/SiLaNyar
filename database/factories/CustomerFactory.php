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
            'nama' => fake()->name(),
            'nik' => fake()->numerify('################'),
            'tanggal_lahir' => fake()->date(),
            'rt' => fake()->numberBetween(1, 99),
            'rw' => fake()->numberBetween(1, 99),
            'kode_pos' => fake()->numberBetween(10000, 99999),
            'alamat' => fake()->address(),
            'username' => fake()->userName(),
            'no_telepon' => '+62' . fake()->numerify('8##########'),
            'email' => fake()->unique()->safeEmail(),
        ];
    }
}
