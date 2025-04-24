<?php

namespace Database\Factories;

use App\Models\kategori;
use App\Models\parameter_uji;
use Illuminate\Database\Eloquent\Factories\Factory;

class kategoriFactory extends Factory
{
    protected $model = kategori::class;

    public function definition(): array
    {
        return [
            'id_parameter' => parameter_uji::factory(),
            'nama' => $this->faker->unique()->word(),
            'harga' => $this->faker->numberBetween(50000, 500000),
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
