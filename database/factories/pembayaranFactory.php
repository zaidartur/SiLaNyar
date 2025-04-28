<?php

namespace Database\Factories;

use App\Models\pembayaran;
use Illuminate\Database\Eloquent\Factories\Factory;

class pembayaranFactory extends Factory
{
    protected $model = pembayaran::class;

    public function definition(): array
    {
        return [
            'total_biaya' => $this->faker->numberBetween(100000, 1000000),
            'tanggal_pembayaran' => $this->faker->date(),
            'metode_pembayaran' => $this->faker->randomElement(['transfer', 'tunai']),
            'status_pembayaran' => $this->faker->randomElement(['selesai', 'belum_dibayar']),
            'bukti_pembayaran' => $this->faker->imageUrl(640, 480, 'payment'),
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
