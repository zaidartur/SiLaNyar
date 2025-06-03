<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ParameterUji>
 */
class ParameterUjiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Common water quality parameters with their typical ranges and units
        $parameters = [
            ['pH', 'pH', 100000],
            ['BOD', 'mg/L', 150000],
            ['COD', 'mg/L', 200000],
            ['TSS', 'mg/L', 125000],
            ['TDS', 'mg/L', 125000],
            ['Ammonia', 'mg/L', 175000],
            ['Nitrat', 'mg/L', 175000],
            ['Fosfat', 'mg/L', 175000],
            ['Kekeruhan', 'NTU', 100000],
            ['DO', 'mg/L', 150000],
            ['Suhu', '°C', 75000],
            ['Minyak & Lemak', 'mg/L', 200000],
            ['Total Coliform', 'MPN/100mL', 250000],
        ];

        $parameter = fake()->unique()->randomElement($parameters);
        static $counter = 1;

        return [
            'nama_parameter' => $parameter[0],
            'satuan' => $parameter[1],
            'harga' => $parameter[2],
        ];
    }
}
