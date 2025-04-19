<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Customer;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
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
            'is_verified' => false,
        ];
    }

    /**
     * Set the customer as verified.
     */
    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_verified' => true,
        ]);
    }
}