<?php

namespace Database\Factories;

use App\Models\jenis_cairan;
use Illuminate\Database\Eloquent\Factories\Factory;

class jenis_cairanFactory extends Factory
{
    protected $model = jenis_cairan::class;

    public function definition(): array
    {
        $min = $this->faker->randomFloat(2, 0.1, 50);
        return [
            'nama' => $this->faker->unique()->word() . ' ' . $this->faker->word(),
            'batas_minimum' => $min,
            'batas_maksimum' => $this->faker->randomFloat(2, $min + 1, $min + 100)
        ];
    }
}
