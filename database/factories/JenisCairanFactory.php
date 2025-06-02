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
        $types = [
            'Air Limbah' => [100, 1000],
            'Air Sumur' => [50, 500],
            'Air Sungai' => [200, 2000],
            'Air PDAM' => [100, 1000],
            'Air Waduk' => [500, 5000],
            'Air Danau' => [500, 5000],
        ];
        
        $nama = fake()->randomElement(array_keys($types));
        [$minRange, $maxRange] = $types[$nama];
        
        return [
            'kode_jenis_cairan' => 'JC-' . str_pad(fake()->unique()->numberBetween(1, 999), 3, '0', STR_PAD_LEFT),
            'nama' => $nama,
            'batas_minimum' => fake()->randomFloat(2, $minRange, $minRange * 1.5),
            'batas_maksimum' => fake()->randomFloat(2, $maxRange * 0.8, $maxRange)
        ];
    }
}