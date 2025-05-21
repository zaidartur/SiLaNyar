<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class KategoriFactory extends Factory
{
    public function definition(): array
    {
        $categories = [
            'Kualitas Air Minum' => 150000,
            'Air Permukaan' => 300000,
            'Air Limbah Domestik' => 500000,
            'Air Limbah Industri' => 750000,
            'Air Tanah' => 500000,
            'Air PDAM' => 1000000
        ];

        $nama = fake()->randomElement(array_keys($categories));
        
        return [
            'nama' => $nama,
            'harga' => $categories[$nama]
        ];
    }
}
