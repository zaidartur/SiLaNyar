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
        $superadmin = Roles::where('name', 'superadmin')->first();
        if ($superadmin) {
            $superadmin->syncPermissions(['kelola user', 'kelola permission', 'kelola role']);
        }

        $adminPermissions = [
            'kelola pembayaran', 'kelola jenis cairan', 'kelola kategori', 'kelola subkategori',
            'kelola parameter', 'kelola aduan', 'lihat pengajuan', 'tambah pengajuan',
            'detail pengajuan', 'edit pengajuan', 'hapus pengajuan', 'lihat pengujian',
            'tambah pengujian', 'edit pengujian', 'detail pengujian', 'hapus pengujian',
            'lihat pengambilan', 'tambah pengambilan', 'detail pengambilan', 'edit pengambilan',
            'hapus pengambilan', 'lihat hasil uji', 'detail hasil uji', 'edit status hasil uji',
            'hapus hasil uji', 'riwayat hasil uji', 'laporan keuangan'
        ];

        $stafAdmin = Roles::where('name', 'staf_administrator')->first();
        if ($stafAdmin) {
            $stafAdmin->syncPermissions($adminPermissions);
        }

        // Kompatibilitas role admin lama
        $legacyAdmin = Roles::where('name', 'admin')->first();
        if ($legacyAdmin) {
            $legacyAdmin->syncPermissions($adminPermissions);
        }

        $analisPermissions = [
            'lihat hasil uji', 'tambah hasil uji', 'edit hasil uji', 'detail hasil uji',
            'hapus hasil uji', 'riwayat hasil uji', 'lihat pengujian', 'detail pengujian',
            'edit pengujian', 'edit status pengujian', 'hapus pengujian', 'lihat pengambilan',
            'detail pengambilan', 'edit pengambilan'
        ];

        $analis = Roles::where('name', 'analis')->first();
        if ($analis) {
            $analis->syncPermissions($analisPermissions);
        }

        // Kompatibilitas role teknisi lama
        $legacyTeknisi = Roles::where('name', 'teknisi')->first();
        if ($legacyTeknisi) {
            $legacyTeknisi->syncPermissions($analisPermissions);
        }

        $pengendaliTeknis = Roles::where('name', 'pengendali_teknis')->first();
        if ($pengendaliTeknis) {
            $pengendaliTeknis->syncPermissions([
                'lihat pengajuan', 'detail pengajuan', 'lihat pengambilan', 'tambah pengambilan',
                'edit pengambilan', 'detail pengambilan', 'lihat pengujian', 'detail pengujian',
                'lihat hasil uji', 'detail hasil uji', 'riwayat hasil uji'
            ]);
        }

        $ppcu = Roles::where('name', 'ppcu')->first();
        if ($ppcu) {
            $ppcu->syncPermissions([
                'lihat pengambilan', 'detail pengambilan', 'edit pengambilan', 'jadwal ppcu', 'pelacakan gps ppcu'
            ]);
        }

        $penyelia = Roles::where('name', 'penyelia')->first();
        if ($penyelia) {
            $penyelia->syncPermissions([
                'lihat pengujian', 'detail pengujian', 'edit pengujian', 'alokasi penyelia',
                'lihat hasil uji', 'detail hasil uji'
            ]);
        }

        $kepalaLab = Roles::where('name', 'kepala_lab')->first();
        if ($kepalaLab) {
            $kepalaLab->syncPermissions([
                'lihat hasil uji', 'detail hasil uji', 'edit status hasil uji', 'riwayat hasil uji',
                'tte kepala lab', 'catatan revisi hasil uji'
            ]);
        }

        $kepalaDinas = Roles::where('name', 'kepala_dinas')->first();
        if ($kepalaDinas) {
            $kepalaDinas->syncPermissions([
                'lihat hasil uji', 'detail hasil uji', 'riwayat hasil uji', 'tte kepala dinas', 'laporan keuangan'
            ]);
        }
    }
}
