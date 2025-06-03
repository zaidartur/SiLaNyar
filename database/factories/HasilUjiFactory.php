<?php

namespace Database\Factories;

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
        return [
            'id_pengujian' => Pengujian::factory(),
            'status' => fake()->randomElement(['draf', 'revisi', 'proses_review', 'proses_peresmian', 'selesai']),
            'proses_review_at' => fake()->optional()->dateTimeThisMonth(),
            'file_pdf' => fake()->boolean(70) ? 'hasil_uji/'. fake()->uuid() .'.pdf' : null,
            'diverifikasi_oleh' => fake()->optional()->name(),
            'created_at' => fake()->dateTime(),
            'updated_at' => fake()->dateTime()
        ];
    }
}
