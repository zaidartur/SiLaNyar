<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\parameter_uji>
 */
class ParameterUjiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_parameter' => fake()->unique()->words(3, true),
            'satuan' => fake()->randomElement(['mg/L', 'µg/L', 'ppm', 'pH', '°C', 'NTU']),
            'baku_mutu' => fake()->randomFloat(2, 0, 1000),
            'harga' => fake()->numberBetween(50000, 500000),
        ];
    }

    /**
     * Configure the factory for high baku mutu parameters.
     */
    public function highBakuMutu(): static
    {
        return $this->state(fn (array $attributes) => [
            'baku_mutu' => fake()->randomFloat(2, 500, 1000),
        ]);
    }

    /**
     * Configure the factory for low baku mutu parameters.
     */
    public function lowBakuMutu(): static
    {
        return $this->state(fn (array $attributes) => [
            'baku_mutu' => fake()->randomFloat(2, 0, 499.99),
        ]);
    }

    /**
     * Configure the factory for expensive parameters.
     */
    public function expensive(): static
    {
        return $this->state(fn (array $attributes) => [
            'harga' => fake()->numberBetween(250001, 500000),
        ]);
    }
}
