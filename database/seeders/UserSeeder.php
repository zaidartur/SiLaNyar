<?php

namespace Database\Seeders;

use App\Models\Instansi;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            // 1. Super Admin
            [
                'role' => 'superadmin',
                'user' => [
                    'nama' => 'Super Administrator',
                    'username' => 'superadmin',
                    'email' => 'superadmin@silanyar.test',
                    'nik' => '3313090101850001',
                    'tanggal_lahir' => '1985-01-01',
                    'rt' => 1,
                    'rw' => 1,
                    'kode_pos' => 57711,
                    'alamat' => 'Jl. Lawu No. 1, Cangakan, Karanganyar',
                    'no_telepon' => '081234567801',
                ],
            ],

            // 2. Staf Administrator
            [
                'role' => 'staf_administrator',
                'user' => [
                    'nama' => 'Ahmad Fauzi, S.Kom. (Staf Admin)',
                    'username' => 'staf_admin',
                    'email' => 'stafadmin@silanyar.test',
                    'nik' => '3313090202880002',
                    'tanggal_lahir' => '1988-02-02',
                    'rt' => 2,
                    'rw' => 1,
                    'kode_pos' => 57712,
                    'alamat' => 'Jl. Lawu No. 45, Bejen, Karanganyar',
                    'no_telepon' => '081234567802',
                ],
            ],

            // 3. Pelanggan (Customer)
            [
                'role' => 'pelanggan',
                'user' => [
                    'nama' => 'Budi Pratama (Pelanggan)',
                    'username' => 'pelanggan',
                    'email' => 'pelanggan@silanyar.test',
                    'nik' => '3313090303920003',
                    'tanggal_lahir' => '1992-03-03',
                    'rt' => 3,
                    'rw' => 2,
                    'kode_pos' => 57713,
                    'alamat' => 'Jl. Solo-Tawangmangu Km. 8, Jaten, Karanganyar',
                    'no_telepon' => '081234567803',
                ],
                'instansi' => [
                    'nama' => 'PT Tirta Karanganyar Makmur',
                    'tipe' => 'swasta',
                    'alamat' => 'Kawasan Industri Jaten, Karanganyar',
                    'wilayah' => '331311',
                    'desa_kelurahan' => '3313112001',
                    'email' => 'kontak@tirtamakmur.co.id',
                    'no_telepon' => '0271891234',
                    'posisi_jabatan' => 'Manajer Lingkungan & HSE',
                    'departemen_divisi' => 'K3 & Pengendalian Dampak Lingkungan',
                    'surat_keterangan_penugasan' => 'surat_tugas_pelanggan.pdf',
                    'foto_kartu_identitas' => 'ktp_pelanggan.jpg',
                ],
            ],

            // 4. Pengendali Teknis
            [
                'role' => 'pengendali_teknis',
                'user' => [
                    'nama' => 'Ir. Hendro Wibowo, S.T. (Pengendali Teknis)',
                    'username' => 'pengendali_teknis',
                    'email' => 'pengendali.teknis@silanyar.test',
                    'nik' => '3313090404830004',
                    'tanggal_lahir' => '1983-04-04',
                    'rt' => 4,
                    'rw' => 2,
                    'kode_pos' => 57714,
                    'alamat' => 'Kompleks Perkantoran Cangakan, Karanganyar',
                    'no_telepon' => '081234567804',
                ],
            ],

            // 5. Petugas PPCU
            [
                'role' => 'ppcu',
                'user' => [
                    'nama' => 'Agus Santoso (Petugas PPCU)',
                    'username' => 'ppcu',
                    'email' => 'ppcu@silanyar.test',
                    'nik' => '3313090505900005',
                    'tanggal_lahir' => '1990-05-05',
                    'rt' => 5,
                    'rw' => 3,
                    'kode_pos' => 57715,
                    'alamat' => 'Jl. Kapten Mulyadi No. 12, Karanganyar',
                    'no_telepon' => '081234567805',
                ],
            ],

            // 6. Penyelia Laboratorium
            [
                'role' => 'penyelia',
                'user' => [
                    'nama' => 'Dewi Lestari, S.Si. (Penyelia Lab)',
                    'username' => 'penyelia',
                    'email' => 'penyelia@silanyar.test',
                    'nik' => '3313090606870006',
                    'tanggal_lahir' => '1987-06-06',
                    'rt' => 1,
                    'rw' => 4,
                    'kode_pos' => 57716,
                    'alamat' => 'Perum Griya Sejahtera No. B-3, Karanganyar',
                    'no_telepon' => '081234567806',
                ],
            ],

            // 7. Analis Laboratorium
            [
                'role' => 'analis',
                'user' => [
                    'nama' => 'Rian Hidayat, A.Md. (Analis Lab)',
                    'username' => 'analis',
                    'email' => 'analis@silanyar.test',
                    'nik' => '3313090707940007',
                    'tanggal_lahir' => '1994-07-07',
                    'rt' => 2,
                    'rw' => 4,
                    'kode_pos' => 57711,
                    'alamat' => 'Jl. Veteran No. 8, Karanganyar',
                    'no_telepon' => '081234567807',
                ],
            ],

            // 8. Kepala Laboratorium
            [
                'role' => 'kepala_lab',
                'user' => [
                    'nama' => 'Dr. Bambang Suryono, M.T. (Kepala Lab)',
                    'username' => 'kepala_lab',
                    'email' => 'kepala.lab@silanyar.test',
                    'nik' => '3313090808760008',
                    'tanggal_lahir' => '1976-08-08',
                    'rt' => 3,
                    'rw' => 5,
                    'kode_pos' => 57712,
                    'alamat' => 'Jl. Lawu No. 100, Karanganyar',
                    'no_telepon' => '081234567808',
                ],
            ],

            // 9. Kepala Dinas Lingkungan Hidup
            [
                'role' => 'kepala_dinas',
                'user' => [
                    'nama' => 'Drs. H. Sriyanto, M.M. (Kepala Dinas LH)',
                    'username' => 'kepala_dinas',
                    'email' => 'kepala.dinas@silanyar.test',
                    'nik' => '3313090909700009',
                    'tanggal_lahir' => '1970-09-09',
                    'rt' => 4,
                    'rw' => 5,
                    'kode_pos' => 57713,
                    'alamat' => 'Jl. Lawu No. 200, Karanganyar',
                    'no_telepon' => '081234567809',
                ],
            ],

            // 10. Role Legacy: Customer
            [
                'role' => 'customer',
                'user' => [
                    'nama' => 'Siti Aminah (Customer Legacy)',
                    'username' => 'customer_legacy',
                    'email' => 'customer@silanyar.test',
                    'nik' => '3313091010930010',
                    'tanggal_lahir' => '1993-10-10',
                    'rt' => 1,
                    'rw' => 6,
                    'kode_pos' => 57714,
                    'alamat' => 'Jl. Palur Raya No. 15, Jaten, Karanganyar',
                    'no_telepon' => '081234567810',
                ],
                'instansi' => [
                    'nama' => 'CV Berkah Tirta Lestari',
                    'tipe' => 'swasta',
                    'alamat' => 'Jl. Raya Palur No. 20, Karanganyar',
                    'wilayah' => '331311',
                    'desa_kelurahan' => '3313112002',
                    'email' => 'info@berkahtirta.com',
                    'no_telepon' => '0271895678',
                    'posisi_jabatan' => 'Penanggung Jawab Lingkungan',
                    'departemen_divisi' => 'Operasional',
                    'surat_keterangan_penugasan' => 'surat_tugas_legacy.pdf',
                    'foto_kartu_identitas' => 'ktp_legacy.jpg',
                ],
            ],

            // 11. Role Legacy: Admin
            [
                'role' => 'admin',
                'user' => [
                    'nama' => 'Tri Wahyuni (Admin Legacy)',
                    'username' => 'admin_legacy',
                    'email' => 'admin.legacy@silanyar.test',
                    'nik' => '3313091111890011',
                    'tanggal_lahir' => '1989-11-11',
                    'rt' => 2,
                    'rw' => 6,
                    'kode_pos' => 57715,
                    'alamat' => 'Jl. Papahan No. 7, Tasikmadu, Karanganyar',
                    'no_telepon' => '081234567811',
                ],
            ],

            // 12. Role Legacy: Teknisi
            [
                'role' => 'teknisi',
                'user' => [
                    'nama' => 'Fajar Nugroho (Teknisi Legacy)',
                    'username' => 'teknisi_legacy',
                    'email' => 'teknisi@silanyar.test',
                    'nik' => '3313091212950012',
                    'tanggal_lahir' => '1995-12-12',
                    'rt' => 3,
                    'rw' => 7,
                    'kode_pos' => 57716,
                    'alamat' => 'Jl. Dagen No. 22, Jaten, Karanganyar',
                    'no_telepon' => '081234567812',
                ],
            ],
        ];

        foreach ($users as $item) {
            $user = User::firstOrCreate(
                ['email' => $item['user']['email']],
                $item['user']
            );

            if (Role::where('name', $item['role'])->exists()) {
                $user->syncRoles([$item['role']]);
            }

            if (isset($item['instansi'])) {
                Instansi::firstOrCreate(
                    ['email' => $item['instansi']['email']],
                    array_merge($item['instansi'], ['id_user' => $user->uuid])
                );
            }
        }
    }
}
