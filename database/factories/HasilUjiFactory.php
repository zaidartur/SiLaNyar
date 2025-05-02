<?php

namespace Database\Factories;

use App\Models\parameter_uji;
use App\Models\pengujian;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\hasil_uji>
 */
class HasilUjiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_parameter' => parameter_uji::factory(),
            'id_pengujian' => pengujian::factory(),
            'nilai' => fake()->randomFloat(2, 0, 100),
            'keterangan' => fake()->sentence(),
            'status' => fake()->optional()->randomElement(['acc', 'revisi']),
        ];
    }

    /**
     * Configure the factory for an accepted hasil uji.
     */
    public function accepted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'acc',
        ]);
    }

    /**
     * Configure the factory for a revised hasil uji.
     */
    public function revised(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'revisi',
        ]);
    }
}
