<?php

namespace Database\Factories;

use App\Models\kategori;
use Illuminate\Database\Eloquent\Factories\Factory;

class kategoriFactory extends Factory
{
    protected $model = kategori::class;

    public function definition(): array
    {
        return [
            'nama' => $this->faker->unique()->word(),
            'harga' => $this->faker->numberBetween(50000, 500000),
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
