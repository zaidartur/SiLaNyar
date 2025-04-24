<?php

namespace Database\Factories;

use App\Models\pegawai;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class pegawaiFactory extends Factory
{
    protected $model = pegawai::class;

    public function definition(): array
    {
        return [
            'nama' => $this->faker->name(),
            'jabatan' => $this->faker->jobTitle(),
            'jenis_kelamin' => $this->faker->randomElement(['laki-laki', 'perempuan']),
            'no_telepon' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('password'),
            'status_verifikasi' => $this->faker->randomElement(['diproses', 'diterima', 'ditolak']),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
