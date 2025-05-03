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
                : '+62' . fake()->numerify('8##########'),
            'otp' => str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT),
            'via' => $isEmail ? 'email' : 'whatsapp',
            'expired_at' => Carbon::now()->addMinutes(15),
        ];
    }
}
