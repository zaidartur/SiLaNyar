<?php

namespace Database\Seeders;

use App\Models\Permissions;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permission = [
            'kelola-permission',
            'kelola-role'
        ];

        $allFitur = [
            'lihat-pegawai',
            'detail-pegawai',
            'verifikasi-pegawai',
            'lihat-pengajuan',
            'detail-pengajuan',
            'edit-pengajuan',
            'lihat-pengujian',
            'tambah-pengujian',
            'edit-pengujian',
            'detail-pengujian',
            'delete-pengujian',
            'lihat-pengambilan',
            'tambah-pengambilan',
            'edit-pengambilan',
            'detail-pengambilan',
            'delete-pengambilan',
            'lihat-jenis_sampel',
            'tambah-jenis_sampel',
            'edit-jenis_sampel',
            'delete-jenis_sampel',
            'lihat-kategori',
            'tambah-kategori',
            'edit-kategori',
            'delete-kategori',
            'lihat-parameter',
            'tambah-parameter',
            'edit-parameter',
            'delete-parameter',
            'lihat-hasil_uji',
            'edit-hasil_uji',
            'detail-hasil_uji',
            'delete-hasil_uji',
            'lihat-pelanggan',
            'detail-pelanggan',
            'verifikasi-customer'
        ];

        foreach ($permission as $per)
        {
            Permissions::firstOrCreate(['name' => $per], ['guard_name' => 'web']);
        }

        foreach ($allFitur as $all)
        {
            Permissions::create(['name' => $all], ['guard_name' => 'web']);
        }
    }
}
