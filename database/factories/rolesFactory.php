<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Roles>
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
            'name' => fake()->unique()->randomElement([
                'Admin Lab',
                'Kepala Lab',
                'Manajer Teknis',
                'Analis Senior',
                'Analis Junior',
                'Staff Admin',
                'Petugas Sampling',
                'Quality Control'
            ]),
            'guard_name' => 'pegawai'
        ];
    }

    /**
     * Configure the factory with basic permissions.
     */
    public function withBasicPermissions(): static
    {
        return $this->afterCreating(function ($role) {
            // Get or create basic permissions based on role name
            $basicPermissions = collect([
                'lihat-pengujian',
                'lihat-hasil_uji',
                'lihat-pengajuan',
                'detail-pengajuan'
            ]);
            
            $role->syncPermissions($basicPermissions);
        });
    }
}
