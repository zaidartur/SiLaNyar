<?php

namespace Database\Seeders;

use App\Models\Pegawai;
use App\Models\Roles;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class TeknisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teknisi = Pegawai::create([
            'nama' => 'Teknisi Keren',
            'email' => 'teknisis@gmail.com',
            'password' => Hash::make('teknisikeren'),
            'jabatan' => 'teknisi',
            'jenis_kelamin' => 'laki-laki',
            'no_telepon' => '+6287878787'
        ]);

        $role = Roles::firstOrCreate(['name' => 'teknisi'], ['guard_name' => 'pegawai']);

        $teknisi->assignRole('teknisi');

        $teknisiPermissions = [
            'lihat-pengujian', 'detail-pengujian',
            'detail-pengambilan', 'lihat-pengambilan',
            'lihat-hasil_uji', 'tambah-hasil_uji', 'detail-hasil_uji', 'delete-hasil_uji'
        ];

        $teknisiPermissionId = Permission::whereIn('name', $teknisiPermissions)->pluck('id')->toArray();

        $teknisi->syncPermissions($teknisiPermissionId);
    }
}
