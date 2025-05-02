<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\roles>
 */
class RolesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(2, true),
            'guard_name' => 'pegawai',
        ];
    }

    /**
     * Configure the factory for common admin roles.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => fake()->randomElement([
                'super admin',
                'admin',
                'manager',
                'supervisor'
            ]),
        ]);
    }

    /**
     * Configure the factory for staff roles.
     */
    public function staff(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => fake()->randomElement([
                'analis',
                'teknisi',
                'operator',
                'staff lab'
            ]),
        ]);
    }

    /**
     * Configure the factory with permissions.
     */
    public function withPermissions(array $permissions = null): static
    {
        return $this->afterCreating(function ($role) use ($permissions) {
            $permissions = $permissions ?? \App\Models\permissions::factory()->count(3)->create();
            $role->syncPermissions($permissions);
        });
    }
}
