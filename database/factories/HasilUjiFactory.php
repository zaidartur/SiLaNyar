<?php

namespace Database\Factories;

use App\Models\ParameterUji;
use App\Models\Pengujian;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HasilUji>
 */
class HasilUjiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $parameter = ParameterUji::inRandomOrder()->first() ?? ParameterUji::factory()->create();
        
        return [
            'id_pengujian' => Pengujian::factory(),
            'id_parameter' => $parameter->id,
            'nilai' => fake()->randomFloat(2, 0, $parameter->baku_mutu * 1.5), // Generate value relative to baku_mutu
            'keterangan' => fake()->randomElement(['Memenuhi baku mutu', 'Melebihi baku mutu', 'Di bawah baku mutu']),
            'status' => fake()->randomElement(['acc', 'revisi', 'draf']),
            'file_pdf' => fake()->boolean(70) ? 'hasil_uji/'. fake()->uuid() .'.pdf' : null
        ];
    }
}
