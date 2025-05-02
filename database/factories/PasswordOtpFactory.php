<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PasswordOtp>
 */
class PasswordOtpFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'identitas' => fake()->unique()->email(),
            'otp' => fake()->numerify('######'), // 6-digit OTP
            'via' => fake()->randomElement(['email', 'whatsapp']),
            'expired_at' => Carbon::now()->addMinutes(15), // Default 15 minutes expiry
        ];
    }

    /**
     * Configure the factory for email OTP.
     */
    public function viaEmail(): static
    {
        return $this->state(fn (array $attributes) => [
            'via' => 'email',
            'identitas' => fake()->unique()->safeEmail(),
        ]);
    }

    /**
     * Configure the factory for WhatsApp OTP.
     */
    public function viaWhatsapp(): static
    {
        return $this->state(fn (array $attributes) => [
            'via' => 'whatsapp',
            'identitas' => fake()->unique()->phoneNumber(),
        ]);
    }

    /**
     * Configure the factory for expired OTP.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'expired_at' => Carbon::now()->subMinutes(fake()->numberBetween(1, 60)),
        ]);
    }
}
