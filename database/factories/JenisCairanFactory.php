<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\jenis_cairan>
 */
class JenisCairanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $min = fake()->randomFloat(2, 0, 50);
        $max = fake()->randomFloat(2, $min + 1, $min + 100);
        
        return [
            'nama' => fake()->unique()->words(2, true),
            'batas_minimum' => $min,
            'batas_maksimum' => $max,
        ];
    }

    /**
     * Configure the factory for a specific range.
     */
    public function withRange(float $min, float $max): static
    {
        return $this->state(fn (array $attributes) => [
            'batas_minimum' => $min,
            'batas_maksimum' => $max,
        ]);
    }
}