<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Permissions>
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
        $actions = ['lihat', 'tambah', 'edit', 'delete', 'detail', 'verifikasi'];
        $resources = [
            'pegawai', 'pengajuan', 'pengujian', 'pengambilan', 
            'jenis_sampel', 'kategori', 'parameter', 'hasil_uji', 
            'pelanggan', 'customer'
        ];

        // Special permissions that don't follow the action-resource pattern
        $specialPermissions = [
            'kelola-permission',
            'kelola-role'
        ];

        return [
            'name' => fake()->randomElement($specialPermissions) ?: 
                     fake()->randomElement($actions) . '-' . fake()->randomElement($resources),
            'guard_name' => 'pegawai',
        ];
    }
}
