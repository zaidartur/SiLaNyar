<?php

namespace Database\Factories;

use App\Models\form_pengajuan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\pembayaran>
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
        return [
            'id_order' => 'ORDER-' . fake()->unique()->numberBetween(100000, 999999),
            'id_form_pengajuan' => form_pengajuan::factory(),
            'total_biaya' => fake()->numberBetween(50000, 1000000),
            'tanggal_pembayaran' => fake()->optional()->date(),
            'metode_pembayaran' => fake()->randomElement(['bank_transfer', 'credit_card', 'gopay', 'qris']),
            'status_pembayaran' => fake()->randomElement(['belum_dibayar', 'menunggu_konfirmasi', 'selesai', 'gagal']),
            'bukti_pembayaran' => fake()->optional()->imageUrl(),
            'id_transaksi' => fake()->optional()->uuid(),
        ];
    }

    /**
     * Configure the factory for a completed payment.
     */
    public function selesai(): static
    {
        return $this->state(fn (array $attributes) => [
            'status_pembayaran' => 'selesai',
            'tanggal_pembayaran' => fake()->dateTimeBetween('-1 month', 'now'),
            'bukti_pembayaran' => fake()->imageUrl(),
            'id_transaksi' => fake()->uuid(),
        ]);
    }

    /**
     * Configure the factory for a pending payment.
     */
    public function menungguKonfirmasi(): static
    {
        return $this->state(fn (array $attributes) => [
            'status_pembayaran' => 'menunggu_konfirmasi',
            'tanggal_pembayaran' => now(),
        ]);
    }

    /**
     * Configure the factory for an unpaid payment.
     */
    public function belumDibayar(): static
    {
        return $this->state(fn (array $attributes) => [
            'status_pembayaran' => 'belum_dibayar',
            'tanggal_pembayaran' => null,
            'bukti_pembayaran' => null,
            'id_transaksi' => null,
        ]);
    }

    /**
     * Configure the factory for a failed payment.
     */
    public function gagal(): static
    {
        return $this->state(fn (array $attributes) => [
            'status_pembayaran' => 'gagal',
            'tanggal_pembayaran' => fake()->dateTimeBetween('-1 month', 'now'),
        ]);
    }
}
