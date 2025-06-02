<?php

namespace Database\Factories;

use App\Models\HasilUji;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class HasilUjiHistoriFactory extends Factory
{
    public function definition(): array
    {
        // Buat data JSON sampel untuk field data_parameterdanpengujian
        $sampleData = [
            'parameter' => [
                [
                    'id' => fake()->numberBetween(1, 10),
                    'nama' => fake()->word(),
                    'baku_mutu' => fake()->randomFloat(2, 0, 100),
                    'nilai' => fake()->randomFloat(2, 0, 100),
                    'keterangan' => fake()->randomElement(['Memenuhi baku mutu', 'Melebihi baku mutu', 'Di bawah baku mutu']),
                ],
                [
                    'id' => fake()->numberBetween(11, 20),
                    'nama' => fake()->word(),
                    'baku_mutu' => fake()->randomFloat(2, 0, 100),
                    'nilai' => fake()->randomFloat(2, 0, 100),
                    'keterangan' => fake()->randomElement(['Memenuhi baku mutu', 'Melebihi baku mutu', 'Di bawah baku mutu']),
                ]
            ],
            'pengujian' => [
                'id' => fake()->numberBetween(1, 5),
                'tanggal_pengujian' => fake()->date(),
                'metode' => fake()->word(),
            ]
        ];

        return [
            'id_hasil_uji' => HasilUji::factory(),
            'data_parameterdanpengujian' => $sampleData,
            'status' => fake()->randomElement(['draf', 'revisi', 'proses_review', 'proses_peresmian', 'selesai']),
            'diupdate_oleh' => fake()->name()
        ];
    }
}
