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
            'kelola permission',
            'kelola role',
            'kelola user',
            'kelola instansi',
            'kelola pembayaran',
            'kelola jenis cairan',
            'kelola kategori',
            'kelola subkategori',
            'kelola parameter',
            'kelola aduan',
            'lihat pengajuan',
            'tambah pengajuan',
            'detail pengajuan',
            'edit pengajuan',
            'hapus pengajuan',
            'lihat pengujian',
            'tambah pengujian',
            'detail pengujian',
            'edit pengujian',
            'hapus pengujian',
            'lihat pengambilan',
            'tambah pengambilan',
            'detail pengambilan',
            'edit pengambilan',
            'hapus pengambilan',
            'lihat hasil uji',
            'tambah hasil uji',
            'detail hasil uji',
            'edit hasil uji',
            'hapus hasil uji',
            'riwayat hasil uji'
        ];

        foreach ($permission as $per) {
            Permissions::firstOrCreate(['name' => $per], ['guard_name' => 'web']);
        }
    }
}
