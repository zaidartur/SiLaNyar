<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KecamatanSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = "
                INSERT INTO `kecamatans` (`kode_kecamatan`, `nama`) VALUES ('331301', 'Jatipuro');
                INSERT INTO `kecamatans` (`kode_kecamatan`, `nama`) VALUES ('331302', 'Jatiyoso');
                INSERT INTO `kecamatans` (`kode_kecamatan`, `nama`) VALUES ('331303', 'Jumapolo');
                INSERT INTO `kecamatans` (`kode_kecamatan`, `nama`) VALUES ('331304', 'Jumantono');
                INSERT INTO `kecamatans` (`kode_kecamatan`, `nama`) VALUES ('331305', 'Matesih');
                INSERT INTO `kecamatans` (`kode_kecamatan`, `nama`) VALUES ('331306', 'Tawangmangu');
                INSERT INTO `kecamatans` (`kode_kecamatan`, `nama`) VALUES ('331307', 'Ngargoyoso');
                INSERT INTO `kecamatans` (`kode_kecamatan`, `nama`) VALUES ('331308', 'Karangpandan');
                INSERT INTO `kecamatans` (`kode_kecamatan`, `nama`) VALUES ('331309', 'Karanganyar');
                INSERT INTO `kecamatans` (`kode_kecamatan`, `nama`) VALUES ('331310', 'Tasikmadu');
                INSERT INTO `kecamatans` (`kode_kecamatan`, `nama`) VALUES ('331311', 'Jaten');
                INSERT INTO `kecamatans` (`kode_kecamatan`, `nama`) VALUES ('331312', 'Colomadu');
                INSERT INTO `kecamatans` (`kode_kecamatan`, `nama`) VALUES ('331313', 'Gondangrejo');
                INSERT INTO `kecamatans` (`kode_kecamatan`, `nama`) VALUES ('331314', 'Kebakkramat');
                INSERT INTO `kecamatans` (`kode_kecamatan`, `nama`) VALUES ('331315', 'Mojogedang');
                INSERT INTO `kecamatans` (`kode_kecamatan`, `nama`) VALUES ('331316', 'Kerjo');
                INSERT INTO `kecamatans` (`kode_kecamatan`, `nama`) VALUES ('331317', 'Jenawi');
        ";

        DB::unprepared($data);
    }
}
