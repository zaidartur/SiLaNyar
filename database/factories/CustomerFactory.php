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
        $jenisUser = $this->faker->randomElement(['perorangan', 'instansi']);
        
        $data = [
            'nama' => 'Wyasana Aji Kusuma Wardana',
            'jenis_user' => $jenisUser,
            'alamat_pribadi' => $this->faker->address(),
            'kontak_pribadi' => '+' . $this->faker->numberBetween(1, 999) . $this->faker->numerify('##########'),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'status_verifikasi' => 'diproses',
        ];
        
        if ($jenisUser === 'instansi') {
            $data['nama_instansi'] = $this->faker->company();
            $data['tipe_instansi'] = $this->faker->randomElement(['swasta', 'pemerintahan']);
            $data['alamat_instansi'] = $this->faker->address();
            $data['kontak_instansi'] = '+' . $this->faker->numberBetween(1, 999) . $this->faker->numerify('##########');
        }
        
        return $data;
    }

    public function accepted(): self
    {
        return $this->state(fn (array $attributes) => [
            'status_verifikasi' => 'diterima',
        ]);
    }

    public function rejected(): self
    {
        return $this->state(fn (array $attributes) => [
            'status_verifikasi' => 'ditolak',
        ]);
    }

    public function processing(): self
    {
        return $this->state(fn (array $attributes) => [
            'status_verifikasi' => 'diproses',
        ]);
    }
}
