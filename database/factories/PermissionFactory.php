<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\permissions>
 */
class PermissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(3, true),
            'guard_name' => 'pegawai',
        ];
    }

    /**
     * Configure the factory for CRUD permissions.
     */
    public function crud(string $resource): static
    {
        return $this->state(function () use ($resource) {
            $action = fake()->randomElement(['create', 'read', 'update', 'delete']);
            return [
                'name' => "{$action} {$resource}",
            ];
        });
    }

    /**
     * Configure the factory for management permissions.
     */
    public function management(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => fake()->randomElement([
                'manage users',
                'manage roles',
                'manage permissions',
                'manage settings',
                'view reports',
                'export data',
            ]),
        ]);
    }
}
