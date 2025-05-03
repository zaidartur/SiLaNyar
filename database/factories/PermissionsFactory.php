<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PermissionsFactory extends Factory
{
    public function definition(): array
    {
        static $permissionCounter = 1;
        
        $actions = ['lihat', 'tambah', 'edit', 'delete', 'detail', 'verifikasi'];
        $resources = [
            'pegawai', 'pengajuan', 'pengujian', 'pengambilan', 
            'jenis_sampel', 'kategori', 'parameter', 'hasil_uji', 
            'pelanggan', 'customer'
        ];

        // Generate unique permission name using counter
        return [
            'name' => 'permission-' . $permissionCounter++,
            'guard_name' => 'pegawai',
        ];
    }

    // Add a state for special permissions
    public function special()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => fake()->unique()->randomElement([
                    'kelola-permission',
                    'kelola-role',
                    'kelola-user',
                    'kelola-system'
                ])
            ];
        });
    }
}
