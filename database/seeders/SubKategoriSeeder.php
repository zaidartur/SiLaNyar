<?php

namespace Database\Seeders;

use App\Models\SubKategori;
use App\Models\ParameterUji;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubKategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bihun = SubKategori::firstOrCreate(['nama' => 'Bihun']);
        $soun = SubKategori::firstOrCreate(['nama' => 'Soun']);
        $bahanFormula = SubKategori::firstOrCreate(['nama' => 'Bahan Formula']);
        $formulasi = SubKategori::firstOrCreate(['nama' => 'Formulasi']);
        $mie = SubKategori::firstOrCreate(['nama' => 'Mie']);
        $kopi = SubKategori::firstOrCreate(['nama' => 'Kopi']);
        $permen = SubKategori::firstOrCreate(['nama' => 'Permen']);
        $bumbuMie = SubKategori::firstOrCreate(['nama' => 'Bumbu Mie']);
        $makananKecil = SubKategori::firstOrCreate(['nama' => 'Makanan Kecil']);
        $golonganI = SubKategori::firstOrCreate(['nama' => 'Golongan Air Limbah I']);
        $golonganII = SubKategori::firstOrCreate(['nama' => 'Golongan Air Limbah II']);
        $industriTahu = SubKategori::firstOrCreate(['nama' => 'Industri Tahu']);
        $industriTempe = SubKategori::firstOrCreate(['nama' => 'Industri Tempe']);
        $kelasI = SubKategori::firstOrCreate(['nama' => 'Kelas I']);
        $kelasII = SubKategori::firstOrCreate(['nama' => 'Kelas II']);
        $kelasIII = SubKategori::firstOrCreate(['nama' => 'Kelas III']);
        $kelasIV = SubKategori::firstOrCreate(['nama' => 'Kelas IV']);

        $suhu = ParameterUji::where('nama_parameter', 'Suhu')->first();
        $ph = ParameterUji::where('nama_parameter', 'Ph')->first();
        $dhl = ParameterUji::where('nama_parameter', 'DHL')->first();
        $tss = ParameterUji::where('nama_parameter', 'TSS')->first();
        $tds = ParameterUji::where('nama_parameter', 'TDS')->first();
        $kekeruhan = ParameterUji::where('nama_parameter', 'Kekeruhan')->first();
        $salinitas = ParameterUji::where('nama_parameter', 'Salinitas')->first();
        $warna = ParameterUji::where('nama_parameter', 'Warna')->first();
        $cod = ParameterUji::where('nama_parameter', 'COD')->first();
        $bod = ParameterUji::where('nama_parameter', 'BOD')->first();
        $phosphat = ParameterUji::where('nama_parameter', 'Phosphat')->first();
        $nitrat = ParameterUji::where('nama_parameter', 'Nitrat')->first();
        $nitrit = ParameterUji::where('nama_parameter', 'Nitrit')->first();
        $mbasDetergent = ParameterUji::where('nama_parameter', 'MBAS Detergent')->first();
        $minyakLemak = ParameterUji::where('nama_parameter', 'Minyak Lemak')->first();
        $minyakNabati = ParameterUji::where('nama_parameter', 'Minyak Nabati')->first();
        $minyakMineral = ParameterUji::where('nama_parameter', 'Minyak Mineral')->first();
        $radioAktifitas = ParameterUji::where('nama_parameter', 'Radio Aktifitas')->first();
        $fenol = ParameterUji::where('nama_parameter', 'Fenol')->first();
        $nh3n = ParameterUji::where('nama_parameter', 'NH3-N')->first();
        $do = ParameterUji::where('nama_parameter', 'DO')->first();
        $sulfida = ParameterUji::where('nama_parameter', 'Sulfida')->first();
        $totalN = ParameterUji::where('nama_parameter', 'Total N')->first();
        $sulfat = ParameterUji::where('nama_parameter', 'Sulfat')->first();
        $kromHeksavalen = ParameterUji::where('nama_parameter', 'Krom Heksavalen')->first();
        $fe = ParameterUji::where('nama_parameter', 'Fe (Besi)')->first();
        $mn = ParameterUji::where('nama_parameter', 'Mn (Mangan)')->first();
        $cu = ParameterUji::where('nama_parameter', 'Cu (Tembaga)')->first();
        $cr = ParameterUji::where('nama_parameter', 'Cr (Krom Total)')->first();
        $cd = ParameterUji::where('nama_parameter', 'Cd (Kadmium)')->first();
        $pb = ParameterUji::where('nama_parameter', 'Pb (Timbal)')->first();
        $zn = ParameterUji::where('nama_parameter', 'Zn (seng)')->first();
        $hg = ParameterUji::where('nama_parameter', 'Hg (Hidroksipropa / Merkuri)')->first();
        $al = ParameterUji::where('nama_parameter', 'Al (Alumunium)')->first();
        $as = ParameterUji::where('nama_parameter', 'As (Arsenikum)')->first();
        $mg = ParameterUji::where('nama_parameter', 'Mg (Magnesium)')->first();
        $ni = ParameterUji::where('nama_parameter', 'Ni (Nikel)')->first();
        $ag = ParameterUji::where('nama_parameter', 'Ag (Argentum / Perak)')->first();
        $ba = ParameterUji::where('nama_parameter', 'Ba (Barium)')->first();
        $se = ParameterUji::where('nama_parameter', 'Se (Selenium)')->first();
        $co = ParameterUji::where('nama_parameter', 'Co (Kobalt)')->first();
        $sn = ParameterUji::where('nama_parameter', 'Sn (Timah)')->first();
        $cn = ParameterUji::where('nama_parameter', 'Cn (Sianida)')->first();
        $h2s = ParameterUji::where('nama_parameter', 'H2s (Sulfida)')->first();
        $f = ParameterUji::where('nama_parameter', 'F (Flourida)')->first();
        $cl = ParameterUji::where('nama_parameter', 'Cl2 (Klorin Bebas)')->first();
        $logamLainnya = ParameterUji::where('nama_parameter', 'Logam Lainnya')->first();
        $totalColiform = ParameterUji::where('nama_parameter', 'Total Coliform')->first();
        $fecalColiform = ParameterUji::where('nama_parameter', 'Fecal Coliform')->first();
        $debitMaksimum = ParameterUji::where('nama_parameter', 'Debit Maksimum')->first();

        $bihun->parameter()->syncWithoutDetaching([
            $bod->id => ['baku_mutu' => '150'],
            $cod->id => ['baku_mutu' => '250'],
            $tss->id => ['baku_mutu' => '100'],
            $ph->id => ['baku_mutu' => '6,0 - 9,0'],
            $debitMaksimum->id => ['baku_mutu' => '10 m³/ton'],
        ]);

        $soun->parameter()->syncWithoutDetaching([
            $bod->id => ['baku_mutu' => '150'],
            $cod->id => ['baku_mutu' => '250'],
            $tss->id => ['baku_mutu' => '100'],
            $ph->id => ['baku_mutu' => '6,0 - 9,0'],
            $debitMaksimum->id => ['baku_mutu' => '15 m³/ton'],
        ]);

        $bahanFormula->parameter()->syncWithoutDetaching([
            $bod->id => ['baku_mutu' => '100'],
            $cod->id => ['baku_mutu' => '300'],
            $tss->id => ['baku_mutu' => '100'],
            $totalN->id => ['baku_mutu' => '30'],
            $fenol->id => ['baku_mutu' => '1'],
            $ph->id => ['baku_mutu' => '6,0 - 9,0'],
        ]);

        $formulasi->parameter()->syncWithoutDetaching([
            $bod->id => ['baku_mutu' => '100'],
            $cod->id => ['baku_mutu' => '300'],
            $tss->id => ['baku_mutu' => '100'],
            $totalN->id => ['baku_mutu' => '-'],
            $fenol->id => ['baku_mutu' => '-'],
            $ph->id => ['baku_mutu' => '6,0 - 9,0'],
        ]);

        $mie->parameter()->syncWithoutDetaching([
            $bod->id => ['baku_mutu' => '50'],
            $cod->id => ['baku_mutu' => '100'],
            $tss->id => ['baku_mutu' => '100'],
            $minyakLemak->id => ['baku_mutu' => '2'],
            $ph->id => ['baku_mutu' => '6,0 - 9,0'],
            $debitMaksimum->id => ['baku_mutu' => '3 m³/ton'],
        ]);

        $kopi->parameter()->syncWithoutDetaching([
            $bod->id => ['baku_mutu' => '50'],
            $cod->id => ['baku_mutu' => '100'],
            $tss->id => ['baku_mutu' => '100'],
            $minyakLemak->id => ['baku_mutu' => '-'],
            $ph->id => ['baku_mutu' => '6,0 - 9,0'],
            $debitMaksimum->id => ['baku_mutu' => '3 m³/ton'],
        ]);

        $permen->parameter()->syncWithoutDetaching([
            $bod->id => ['baku_mutu' => '50'],
            $cod->id => ['baku_mutu' => '100'],
            $tss->id => ['baku_mutu' => '75'],
            $minyakLemak->id => ['baku_mutu' => '-'],
            $ph->id => ['baku_mutu' => '6,0 - 9,0'],
            $debitMaksimum->id => ['baku_mutu' => '5 m³/ton'],
        ]);

        $bumbuMie->parameter()->syncWithoutDetaching([
            $bod->id => ['baku_mutu' => '50'],
            $cod->id => ['baku_mutu' => '100'],
            $tss->id => ['baku_mutu' => '100'],
            $minyakLemak->id => ['baku_mutu' => '2'],
            $ph->id => ['baku_mutu' => '6,0 - 9,0'],
            $debitMaksimum->id => ['baku_mutu' => '5 m³/ton'],
        ]);

        $makananKecil->parameter()->syncWithoutDetaching([
            $bod->id => ['baku_mutu' => '50'],
            $cod->id => ['baku_mutu' => '100'],
            $tss->id => ['baku_mutu' => '100'],
            $minyakLemak->id => ['baku_mutu' => '2'],
            $ph->id => ['baku_mutu' => '6,0 - 9,0'],
            $debitMaksimum->id => ['baku_mutu' => '5 m³/ton'],
        ]);

        $golonganI->parameter()->syncWithoutDetaching([
            $suhu->id => ['baku_mutu' => '38'],
            $tds->id => ['baku_mutu' => '2000'],
            $tss->id => ['baku_mutu' => '100'],
            $ph->id => ['baku_mutu' => '6,0 - 9,0'],
            $fe->id => ['baku_mutu' => '5'],
            $mn->id => ['baku_mutu' => '2'],
            $ba->id => ['baku_mutu' => '2'],
            $cu->id => ['baku_mutu' => '2'],
            $zn->id => ['baku_mutu' => '5'],
            $kromHeksavalen->id => ['baku_mutu' => '0.1'],
            $cr->id => ['baku_mutu' => '0.5'],
            $cd->id => ['baku_mutu' => '0.05'],
            $hg->id => ['baku_mutu' => '0.002'],
            $pb->id => ['baku_mutu' => '0.1'],
            $sn->id => ['baku_mutu' => '2'],
            $as->id => ['baku_mutu' => '0.1'],
            $se->id => ['baku_mutu' => '0.05'],
            $ni->id => ['baku_mutu' => '0.2'],
            $co->id => ['baku_mutu' => '0.4'],
            $cn->id => ['baku_mutu' => '0.05'],
            $h2s->id => ['baku_mutu' => '0.05'],
            $f->id => ['baku_mutu' => '2'],
            $cl->id => ['baku_mutu' => '1'],
            $nitrat->id => ['baku_mutu' => '20'],
            $nitrit->id => ['baku_mutu' => '1'],
            $bod->id => ['baku_mutu' => '50'],
            $cod->id => ['baku_mutu' => '100'],
            $mbasDetergent->id => ['baku_mutu' => '5'],
            $fenol->id => ['baku_mutu' => '0.5'],
            $minyakNabati->id => ['baku_mutu' => '5'],
            $minyakMineral->id => ['baku_mutu' => '10'],
            $radioAktifitas->id => ['baku_mutu' => '-'],
        ]);

        $golonganII->parameter()->syncWithoutDetaching([
            $suhu->id => ['baku_mutu' => '38'],
            $tds->id => ['baku_mutu' => '4000'],
            $tss->id => ['baku_mutu' => '200'],
            $ph->id => ['baku_mutu' => '6,0 - 9,0'],
            $fe->id => ['baku_mutu' => '10'],
            $mn->id => ['baku_mutu' => '5'],
            $ba->id => ['baku_mutu' => '3'],
            $cu->id => ['baku_mutu' => '3'],
            $zn->id => ['baku_mutu' => '10'],
            $kromHeksavalen->id => ['baku_mutu' => '0.5'],
            $cr->id => ['baku_mutu' => '1'],
            $cd->id => ['baku_mutu' => '0.10'],
            $hg->id => ['baku_mutu' => '0.005'],
            $pb->id => ['baku_mutu' => '1'],
            $sn->id => ['baku_mutu' => '3'],
            $as->id => ['baku_mutu' => '0.5'],
            $se->id => ['baku_mutu' => '0.5'],
            $ni->id => ['baku_mutu' => '0.5'],
            $co->id => ['baku_mutu' => '0.6'],
            $cn->id => ['baku_mutu' => '0.5'],
            $h2s->id => ['baku_mutu' => '0.1'],
            $f->id => ['baku_mutu' => '3'],
            $cl->id => ['baku_mutu' => '2'],
            $nitrat->id => ['baku_mutu' => '30'],
            $nitrit->id => ['baku_mutu' => '3'],
            $bod->id => ['baku_mutu' => '100'],
            $cod->id => ['baku_mutu' => '250'],
            $mbasDetergent->id => ['baku_mutu' => '100.5'],
            $fenol->id => ['baku_mutu' => '1'],
            $minyakNabati->id => ['baku_mutu' => '10'],
            $minyakMineral->id => ['baku_mutu' => '50'],
            $radioAktifitas->id => ['baku_mutu' => '-'],
        ]);

        $industriTahu->parameter()->syncWithoutDetaching([
            $suhu->id => ['baku_mutu' => '38'],
            $bod->id => ['baku_mutu' => '150'],
            $cod->id => ['baku_mutu' => '275'],
            $tss->id => ['baku_mutu' => '100'],
            $ph->id => ['baku_mutu' => '6,0 - 9,0'],
            $debitMaksimum->id => ['baku_mutu' => '20 m³/ton kedelai']
        ]);

        $industriTempe->parameter()->syncWithoutDetaching([
            $suhu->id => ['baku_mutu' => '38'],
            $bod->id => ['baku_mutu' => '150'],
            $cod->id => ['baku_mutu' => '275'],
            $tss->id => ['baku_mutu' => '100'],
            $ph->id => ['baku_mutu' => '6,0 - 9,0'],
            $debitMaksimum->id => ['baku_mutu' => '10 m³/ton kedelai']
        ]);

        $kelasI->parameter()->syncWithoutDetaching([
            $ph->id => ['baku_mutu' => '6,0 - 9,0'],
            $bod->id => ['baku_mutu' => '2'],
            $cod->id => ['baku_mutu' => '10'],
            $tss->id => ['baku_mutu' => '40'],
            $do->id => ['baku_mutu' => '6'],
            $phosphat->id => ['baku_mutu' => '0.2'],
            $totalColiform->id => ['baku_mutu' => '1000'],
        ]);

        $kelasII->parameter()->syncWithoutDetaching([
            $ph->id => ['baku_mutu' => '6,0 - 9,0'],
            $bod->id => ['baku_mutu' => '3'],
            $cod->id => ['baku_mutu' => '25'],
            $tss->id => ['baku_mutu' => '50'],
            $do->id => ['baku_mutu' => '4'],
            $phosphat->id => ['baku_mutu' => '0.2'],
            $totalColiform->id => ['baku_mutu' => '5000'],
        ]);

        $kelasIII->parameter()->syncWithoutDetaching([
            $ph->id => ['baku_mutu' => '6,0 - 9,0'],
            $bod->id => ['baku_mutu' => '6'],
            $cod->id => ['baku_mutu' => '40'],
            $tss->id => ['baku_mutu' => '100'],
            $do->id => ['baku_mutu' => '3'],
            $phosphat->id => ['baku_mutu' => '1.0'],
            $totalColiform->id => ['baku_mutu' => '10000'],
        ]);

        $kelasIV->parameter()->syncWithoutDetaching([
            $ph->id => ['baku_mutu' => '6,0 - 9,0'],
            $bod->id => ['baku_mutu' => '12'],
            $cod->id => ['baku_mutu' => '80'],
            $tss->id => ['baku_mutu' => '400'],
            $do->id => ['baku_mutu' => '1'],
            $phosphat->id => ['baku_mutu' => '-'],
            $totalColiform->id => ['baku_mutu' => '5000'],
        ]);

        $this->command->info('Subkategori dan Parameter Uji dengan baku mutu berhasil dilampirkan!');
    }
}
