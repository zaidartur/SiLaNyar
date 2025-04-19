<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        $jenis_user = $this->faker->randomElement(['instansi', 'perorangan']);
        
        return [
            'nama' => $this->faker->name(),
            'jenis_user' => $jenis_user,
            'alamat_pribadi' => $this->faker->address(),
            'kontak_pribadi' => '+' . $this->faker->numerify('###########'),
            'nama_instansi' => $jenis_user === 'instansi' ? $this->faker->company() : null,
            'tipe_instansi' => $jenis_user === 'instansi' ? $this->faker->randomElement(['swasta', 'pemerintahan']) : null,
            'alamat_instansi' => $jenis_user === 'instansi' ? $this->faker->address() : null,
            'kontak_instansi' => $jenis_user === 'instansi' ? '+' . $this->faker->numerify('###########') : null,
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            // Removed is_verified field
        ];
    }

    /**
     * Set the customer's email as verified.
     */
    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => now(),
        ]);
    }

    /**
     * Set the customer's email as unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}