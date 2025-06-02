<?php

namespace Database\Factories;

use App\Models\FormPengajuan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pembayaran>
 */
class PembayaranFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = fake()->randomElement(['diproses', 'selesai', 'gagal']);
        $isComplete = $status === 'selesai';
        $methodPayment = $isComplete ? fake()->randomElement(['tunai', 'transfer']) : null;
        
        return [
            'id_order' => 'ORD-' . date('Ymd') . '-' . str_pad(fake()->unique()->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT),
            'id_form_pengajuan' => FormPengajuan::factory(),
            'total_biaya' => fake()->numberBetween(100000, 1000000),
            'tanggal_pembayaran' => $isComplete ? now() : null,
            'metode_pembayaran' => $methodPayment,
            'status_pembayaran' => $status,
            'bukti_pembayaran' => ($isComplete && $methodPayment === 'transfer') ? 'pembayaran/'.fake()->uuid().'.jpg' : null,
        ];
    }

    public function selesai(): static
    {
        return $this->state(function (array $attributes) {
            $metode = fake()->randomElement(['tunai', 'transfer']);
            return [
                'status_pembayaran' => 'selesai',
                'tanggal_pembayaran' => now(),
                'metode_pembayaran' => $metode,
                'bukti_pembayaran' => $metode === 'transfer' ? 'pembayaran/'.fake()->uuid().'.jpg' : null,
            ];
        });
    }

    public function diproses(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status_pembayaran' => 'diproses',
                'tanggal_pembayaran' => null,
                'metode_pembayaran' => null,
                'bukti_pembayaran' => null,
            ];
        });
    }

    public function gagal(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status_pembayaran' => 'gagal',
                'tanggal_pembayaran' => null,
                'metode_pembayaran' => null,
                'bukti_pembayaran' => null,
            ];
        });
    }
}
