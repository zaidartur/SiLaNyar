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
            ['pH', 'pH', 7.0, 100000],
            ['BOD', 'mg/L', 30.0, 150000],
            ['COD', 'mg/L', 100.0, 200000],
            ['TSS', 'mg/L', 50.0, 125000],
            ['TDS', 'mg/L', 1000.0, 125000],
            ['Ammonia', 'mg/L', 10.0, 175000],
            ['Nitrat', 'mg/L', 10.0, 175000],
            ['Fosfat', 'mg/L', 2.0, 175000],
            ['Kekeruhan', 'NTU', 25.0, 100000],
            ['DO', 'mg/L', 4.0, 150000],
            ['Suhu', '°C', 30.0, 75000],
            ['Minyak & Lemak', 'mg/L', 5.0, 200000],
            ['Total Coliform', 'MPN/100mL', 1000.0, 250000],
        ];

        $parameter = fake()->unique()->randomElement($parameters);

        return [
            'nama_parameter' => $parameter[0],
            'satuan' => $parameter[1],
            'baku_mutu' => $parameter[2],
            'harga' => $parameter[3],
        ];
    }
}
