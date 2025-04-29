<?php

namespace Database\Factories;

use App\Models\permissions;
use Illuminate\Database\Eloquent\Factories\Factory;

class permissionsFactory extends Factory
{
    protected $model = permissions::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
            'guard_name' => 'pegawai',
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
