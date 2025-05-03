<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kategori>
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
            'nama' => fake()->randomElement([
                'Kualitas Air Minum',
                'Air Permukaan',
                'Air Limbah Domestik',
                'Air Limbah Industri',
                'Air Tanah',
                'Air PDAM'
            ]),
            'harga' => fake()->randomElement([
                150000,  // Basic testing package
                300000,  // Standard testing package
                500000,  // Complete testing package
                750000,  // Professional testing package
                1000000  // Comprehensive testing package
            ])
        ];
    }
}
