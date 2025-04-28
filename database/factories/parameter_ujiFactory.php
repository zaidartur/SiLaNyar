<?php

namespace Database\Factories;

use App\Models\parameter_uji;
use Illuminate\Database\Eloquent\Factories\Factory;

class parameter_ujiFactory extends Factory
{
    protected $model = parameter_uji::class;

    public function definition(): array
    {
        return [
            'nama_parameter' => $this->faker->unique()->word(),
            'satuan' => $this->faker->randomElement(['mg/L', 'µg/L', 'ppm', 'NTU', 'pH']),
            'baku_mutu' => $this->faker->randomFloat(2, 0.01, 100),
            'harga' => $this->faker->numberBetween(50000, 500000),
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
