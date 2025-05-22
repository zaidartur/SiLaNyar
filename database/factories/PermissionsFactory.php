<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PermissionsFactory extends Factory
{
    protected $actions = ['lihat', 'tambah', 'edit', 'hapus', 'detail', 'verifikasi'];
    protected $resources = [
        'pegawai', 'pengajuan', 'pengujian', 'pengambilan', 
        'jenis_sampel', 'kategori', 'parameter', 'hasil_uji', 
        'pelanggan', 'customer'
    ];

    public function definition(): array
    {
        $action = fake()->randomElement($this->actions);
        $resource = fake()->randomElement($this->resources);
        
        return [
            'name' => $action . '-' . $resource,
            'guard_name' => 'web',
        ];
    }

    public function special()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => fake()->unique()->randomElement([
                    'kelola-permission',
                    'kelola-role',
                    'kelola-user',
                    'kelola-system'
                ]),
                'guard_name' => 'web'
            ];
        });
    }
}
