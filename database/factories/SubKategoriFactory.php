<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubKategori>
 */
class SubKategoriFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subkategoriNames = [
            'Air Permukaan Kelas I',
            'Air Permukaan Kelas II',
            'Air Permukaan Kelas III',
            'Air Permukaan Kelas IV',
            'Air Limbah Domestik',
            'Air Limbah Industri',
            'Air Minum',
            'Air Bersih',
            'Air Baku',
            'Air Tanah',
            'Air Laut',
            'Air Kolam Renang',
            'Air Pemandian Umum'
        ];
        
        static $counter = 1;
        
        return [
            'kode_subkategori' => 'SK-' . str_pad($counter++, 3, '0', STR_PAD_LEFT),
            'nama' => fake()->unique()->randomElement($subkategoriNames),
        ];
    }
}
