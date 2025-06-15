<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Roles;

class GivePermissionToRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Roles::findByName('superadmin');
        $role->givePermissionTo(['kelola user', 'kelola permission', 'kelola role']);
        $role = Roles::findByName('admin');
        $role->givePermissionTo(['kelola pembayaran', 'kelola jenis cairan', 'kelola kategori', 'kelola subkategori','kelola parameter', 'kelola aduan', 'lihat pengajuan', 'tambah pengajuan', 'detail pengajuan', 'edit pengajuan', 'hapus pengajuan', 'lihat pengujian', 'tambah pengujian', 'edit pengujian', 'detail pengujian', 'hapus pengujian', 'lihat pengambilan', 'tambah pengambilan', 'detail pengambilan', 'edit pengambilan', 'hapus pengambilan', 'lihat hasil uji', 'detail hasil uji', 'edit status hasil uji', 'hapus hasil uji', 'riwayat hasil uji', 'laporan keuangan']);
        $role = Roles::findByName('teknisi');
        $role->givePermissionTo(['lihat hasil uji', 'tambah hasil uji', 'edit hasil uji', 'detail hasil uji', 'hapus hasil uji', 'riwayat hasil uji', 'lihat pengujian', 'detail pengujian', 'edit pengujian', 'hapus pengujian', 'lihat pengambilan', 'detail pengambilan', 'edit pengambilan']);
    }
}
