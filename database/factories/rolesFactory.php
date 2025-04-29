<?php

namespace Database\Factories;

use App\Models\roles;
use Illuminate\Database\Eloquent\Factories\Factory;

class rolesFactory extends Factory
{
    protected $model = roles::class;

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
