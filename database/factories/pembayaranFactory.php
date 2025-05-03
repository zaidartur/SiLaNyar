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
        $status = fake()->randomElement(['pending', 'dibayar', 'expired', 'gagal']);
        $isPaid = $status === 'dibayar';
        
        return [
            'id_order' => 'LAB-' . date('Ymd') . '-' . fake()->unique()->numerify('####'),
            'id_form_pengajuan' => FormPengajuan::factory()->create([
                'status_pengajuan' => 'diterima'
            ]),
            'total_biaya' => fake()->randomElement([
                150000,  // Basic package
                300000,  // Standard package
                500000,  // Complete package
                750000,  // Professional package
                1000000  // Comprehensive package
            ]),
            'tanggal_pembayaran' => $isPaid ? now() : null,
            'metode_pembayaran' => $isPaid ? fake()->randomElement(['QRIS', 'Bank Transfer', 'Virtual Account']) : null,
            'status_pembayaran' => $status,
            'bukti_pembayaran' => $isPaid ? 'bukti_pembayaran/payment-'.fake()->uuid().'.jpg' : null,
            'id_transaksi' => $isPaid ? 'TRX-'.fake()->uuid() : null,
        ];
    }

    public function dibayar(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status_pembayaran' => 'dibayar',
                'tanggal_pembayaran' => now(),
                'metode_pembayaran' => fake()->randomElement(['QRIS', 'Bank Transfer', 'Virtual Account']),
                'bukti_pembayaran' => 'bukti_pembayaran/payment-'.fake()->uuid().'.jpg',
                'id_transaksi' => 'TRX-'.fake()->uuid(),
            ];
        });
    }
}
