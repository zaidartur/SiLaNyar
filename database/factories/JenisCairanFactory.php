<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class JenisCairanFactory extends Factory
{
    protected static $counter = 0;
    protected $types = [
        'Air Limbah',
        'Air Sumur',
        'Air Sungai',
        'Air PDAM',
        'Air Waduk',
        'Air Danau'
    ];

    public function definition(): array
    {
        $ranges = [
            'min' => [100, 50, 200, 100, 500, 500],
            'max' => [1000, 500, 2000, 1000, 5000, 5000]
        ];

        // Memastikan nama unik dengan menggunakan counter
        $index = static::$counter % count($this->types);
        $nama = $this->types[$index] . ' ' . fake()->unique()->word();
        static::$counter++;

        return [
            'nama' => $nama,
            'batas_minimum' => fake()->randomFloat(2, $ranges['min'][$index], $ranges['min'][$index] * 1.5),
            'batas_maksimum' => fake()->randomFloat(2, $ranges['max'][$index] * 0.8, $ranges['max'][$index])
        ];
    }
}