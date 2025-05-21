<?php

namespace Database\Factories;

use App\Models\HasilUji;
use App\Models\ParameterUji;
use App\Models\Pengujian;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class HasilUjiHistoriFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id_hasil_uji' => HasilUji::factory(),
            'id_parameter' => ParameterUji::factory(),
            'id_pengujian' => Pengujian::factory(),
            'id_user' => User::factory(),
            'nilai' => fake()->randomFloat(2, 0, 100),
            'keterangan' => fake()->sentence(),
            'status' => fake()->randomElement(['acc', 'revisi', 'draf'])
        ];
    }
}
