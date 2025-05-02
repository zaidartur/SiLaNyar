<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JenisCairan>
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
        // Common liquid volume ranges (in mL)
        $min = fake()->randomFloat(2, 100, 500);
        $max = fake()->randomFloat(2, $min + 500, 5000);
        
        return [
            'nama' => fake()->randomElement([
                'Air Limbah',
                'Air Sumur',
                'Air Sungai',
                'Air PDAM',
                'Air Waduk',
                'Air Danau',
            ]),
            'batas_minimum' => $min,
            'batas_maksimum' => $max,
        ];
    }
}