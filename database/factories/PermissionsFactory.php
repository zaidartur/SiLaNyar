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
        // Use fake()->unique() to ensure unique permission names
        $action = fake()->randomElement($this->actions);
        $resource = fake()->randomElement($this->resources);
        $suffix = fake()->unique()->numberBetween(1, 99999);
        
        return [
            'name' => $action . ' ' . $resource . ' ' . $suffix,
            'guard_name' => 'web',
        ];
    }

    public function special()
    {
        return $this->state(function (array $attributes) {
            $suffix = fake()->unique()->numberBetween(1, 99999);
            return [
                'name' => fake()->randomElement([
                    'kelola permission',
                    'kelola role', 
                    'kelola user',
                    'kelola system'
                ]) . ' ' . $suffix,
                'guard_name' => 'web'
            ];
        });
    }
}
