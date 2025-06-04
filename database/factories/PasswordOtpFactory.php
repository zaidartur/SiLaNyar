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
        $isEmail = fake()->boolean();
        
        return [
            'identitas' => $isEmail 
                ? fake()->safeEmail() 
                : '+62' . fake()->numerify('8##########'), // 11 digit
            'otp' => str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT),
            'via' => $isEmail ? 'email' : 'whatsapp',
        ];
    }

    /**
     * Set custom expired_at time
     */
    public function expired($minutes = -1): static
    {
        return $this->state(fn (array $attributes) => [
            'expired_at' => now()->addMinutes($minutes)
        ]);
    }
}
