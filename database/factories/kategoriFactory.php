<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\kategori>
 */
class KategoriFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->unique()->words(2, true),
            'harga' => fake()->numberBetween(50000, 1000000),
        ];
    }

    /**
     * Configure the factory for a high-price category.
     */
    public function highPrice(): static
    {
        return $this->state(fn (array $attributes) => [
            'harga' => fake()->numberBetween(500000, 1000000),
        ]);
    }

    /**
     * Configure the factory for a low-price category.
     */
    public function lowPrice(): static
    {
        return $this->state(fn (array $attributes) => [
            'harga' => fake()->numberBetween(50000, 499999),
        ]);
    }
}
