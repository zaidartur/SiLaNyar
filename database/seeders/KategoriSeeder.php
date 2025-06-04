<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kategori;
use App\Models\SubKategori;
use App\Models\ParameterUji;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $industriBihunSoun = Kategori::firstOrCreate(['nama' => 'Baku Mutu Air Limbah Industri Bihun dan Soun Berdasarkan Perda Jateng No.5 Tahun 2012', 'harga' => 240000]);
        $industriCatTinta = Kategori::firstOrCreate(['nama' => 'Baku Mutu Air Limbah Industri Cat Dan Tinta Berdasarkan Perda Jateng No.5 Tahun 2012', 'harga' => 1020000]);
        $industriFarmasi = Kategori::firstOrCreate(['nama' => 'Baku Mutu Air Limbah Industri FARMASI Berdasarkan Perda Jateng No.5 Tahun 2012', 'harga' => 560000]);
        $industriJamu = Kategori::firstOrCreate(['nama' => 'Baku Mutu Air Limbah Industri JAMU Berdasarkan Perda Jateng No.5 Tahun 2012', 'harga' => 390000]);
        $industriKaret = Kategori::firstOrCreate(['nama' => 'Baku Mutu Air Limbah Industri KARET Berdasarkan Perda Jateng No.5 Tahun 2012', 'harga' => 340000]);
        $industriTekstilBatik = Kategori::firstOrCreate(['nama' => 'Baku Mutu Air Limbah Industri Tekstil dan Batik Berdasarkan Perda Jateng No.5 Tahun 2012', 'harga' => 750000]);
        $domestik = Kategori::firstOrCreate(['nama' => 'Baku Mutu Air Limbah Industri DOMESTIK Berdasarkan Permen LHK No.68 Tahun 2016', 'harga' => 515000]);
        $makananSpesifik = Kategori::firstOrCreate(['nama' => 'Baku Mutu Air Limbah Industri Makanan Spesifik Berdasarkan Perda Jateng No.5 Tahun 2012', 'harga' => 340000]);
        $pelapisanLogam = Kategori::firstOrCreate(['nama' => 'Baku Mutu Air Limbah Industri Pelapisan Logam Berdasarkan Perda Jateng No.5 Tahun 2012', 'harga' => 570000]);
        // $belumDitetapkanBakuMutunya = Kategori::firstOrCreate(['nama' => 'Baku Mutu Air Limbah Untuk Usaha Dan/Atau Kegiatan Yang Belum Ditetapkan Baku Mutunya (Lampiran IX) Berdasarkan Perda Jateng No.5 Tahun 2012']);
        // $airLindi = Kategori::firstOrCreate(['nama' => 'Baku Mutu Air Lindi Berdasarkan Permen LHK No.59 Tahun 2016']);
        $tahuTempe = Kategori::firstOrCreate(['nama' => 'Baku Mutu Air Limbah Tahu dan Tempe Berdasarkan Perda Jateng No.5 Tahun 2012', 'harga' => 250000]);
        // $sungai = Kategori::firstOrCreate(['nama' => 'Baku Mutu Air Sungai Berdasarkan PP No.22 Tahun 2021']);
        $kayuLapis = Kategori::firstOrCreate(['nama' => 'Baku Mutu Industri Kayu Lapis Berdasarkan Permen LHK No.5 Tahun 2014', 'harga' => 490000]);

        $bihun = SubKategori::where('nama', 'Bihun')->first();
        $soun = SubKategori::where('nama', 'Soun')->first();
        $bahanFormula = SubKategori::where('nama', 'Bahan Formula')->first();
        $formulasi = SubKategori::where('nama', 'Formulasi')->first();
        $mie = SubKategori::where('nama', 'Mie')->first();
        $kopi = SubKategori::where('nama', 'Kopi')->first();
        $permen = SubKategori::where('nama', 'Permen')->first();
        $bumbuMie = SubKategori::where('nama', 'Bumbu Mie')->first();
        $makananKecil = SubKategori::where('nama', 'Makanan Kecil')->first();
        $golonganI = SubKategori::where('nama', 'Golongan Air Limbah I')->first();
        $golonganII = SubKategori::where('nama', 'Golongan Air Limbah II')->first();
        $industriTahu = SubKategori::where(['nama' => 'Industri Tahu'])->first();
        $industriTempe = SubKategori::where(['nama' => 'Industri Tempe'])->first();
        $kelas1 = SubKategori::where(['nama' => 'Kelas I'])->first();
        $kelas2 = SubKategori::where(['nama' => 'Kelas II'])->first();
        $kelas3 = SubKategori::where(['nama' => 'Kelas III'])->first();
        $kelas4 = SubKategori::where(['nama' => 'Kelas IV'])->first();

        $suhu = ParameterUji::where('nama_parameter', 'Suhu')->first();
        $ph = ParameterUji::where('nama_parameter', 'Ph')->first();
        $tss = ParameterUji::where('nama_parameter', 'TSS')->first();
        $cod = ParameterUji::where('nama_parameter', 'COD')->first();
        $bod = ParameterUji::where('nama_parameter', 'BOD')->first();
        $merkuri = ParameterUji::where('nama_parameter', 'Hg (Hidroksipropa / Merkuri)')->first();
        $seng = ParameterUji::where('nama_parameter', 'Zn (seng)')->first();
        $timbal = ParameterUji::where('nama_parameter', 'Pb (Timbal)')->first();
        $tembaga = ParameterUji::where('nama_parameter', 'Cu (Tembaga)')->first();
        $khromHexavalen = ParameterUji::where('nama_parameter', 'Krom Heksavalen')->first();
        $titanium = ParameterUji::where('nama_parameter', 'Ti (Titanium)')->first();
        $kadmium = ParameterUji::where('nama_parameter', 'Cd (Kadmium)')->first();
        $fenol = ParameterUji::where('nama_parameter', 'Fenol')->first();
        $minyakLemak = ParameterUji::where('nama_parameter', 'Minyak Lemak')->first();
        $debitMaksimum = ParameterUji::where('nama_parameter', 'Debit Maksimum')->first();
        $totalN = ParameterUji::where('nama_parameter', 'Total N')->first();
        $amonia = ParameterUji::where('nama_parameter', 'NH3-N')->first();
        $sulfida = ParameterUji::where('nama_parameter', 'Sulfida')->first();
        $khromTotal = ParameterUji::where('nama_parameter', 'Cr (Krom Total)')->first();
        $totalColiform = ParameterUji::where('nama_parameter', 'Total Coliform')->first();
        $sianida = ParameterUji::where('nama_parameter', 'Cn (Sianida)')->first();
        $nikel = ParameterUji::where('nama_parameter', 'Ni (Nikel)')->first();
        $tds = ParameterUji::where('nama_parameter', 'TDS')->first();
        $minyakNabati = ParameterUji::where('nama_parameter', 'Minyak Nabati')->first();
        $minyakMineral = ParameterUji::where('nama_parameter', 'Minyak Mineral')->first();
        $radioAktifitas = ParameterUji::where('nama_parameter', 'Radio Aktifitas')->first();
        $do = ParameterUji::where('nama_parameter', 'DO')->first();
        $phosphat = ParameterUji::where('nama_parameter', 'Phosphat')->first();

        $industriBihunSoun->subKategori()->syncWithoutDetaching([
            $bihun->id,
            $soun->id,
        ]);

        $industriCatTinta->parameter()->syncWithoutDetaching([
            $bod->id => ['baku_mutu' => '80'],
            $cod->id => ['baku_mutu' => '150'],
            $tss->id => ['baku_mutu' => '50'],
            $merkuri->id => ['baku_mutu' => '0.01'],
            $seng->id => ['baku_mutu' => '1.0'],
            $timbal->id => ['baku_mutu' => '0.30'],
            $tembaga->id => ['baku_mutu' => '0.80'],
            $khromHexavalen->id => ['baku_mutu' => '0.20'],
            $titanium->id => ['baku_mutu' => '0.40'],
            $kadmium->id => ['baku_mutu' => '0.08'],
            $fenol->id => ['baku_mutu' => '0.20'],
            $minyakLemak->id => ['baku_mutu' => '10'],
            $ph->id => ['baku_mutu' => '6.0 - 9.0'],
            $debitMaksimum->id => ['baku_mutu' => '0.5 liter per liter produk cat water base, Zero discharge untuk cat solvent base'],
        ]);

        $industriFarmasi->subKategori()->syncWithoutDetaching([
            $bahanFormula->id,
            $formulasi->id,
        ]);

        $industriJamu->parameter()->syncWithoutDetaching([
            $bod->id => ['baku_mutu' => '60'],
            $cod->id => ['baku_mutu' => '120'],
            $tss->id => ['baku_mutu' => '60'],
            $fenol->id => ['baku_mutu' => '0.2'],
            $ph->id => ['baku_mutu' => '6.0 - 9.0'],
            $debitMaksimum->id => ['baku_mutu' => '3/ton bahan baku'],
        ]);

         $industriKaret->parameter()->syncWithoutDetaching([
            $bod->id => ['baku_mutu' => '150'],
            $cod->id => ['baku_mutu' => '300'],
            $tss->id => ['baku_mutu' => '150'],
            $amonia->id => ['baku_mutu' => '10'],
            $ph->id => ['baku_mutu' => '6.0 - 9.0'],
            $debitMaksimum->id => ['baku_mutu' => '40 m³/ton produk karet'],
        ]);

        $industriTekstilBatik->parameter()->syncWithoutDetaching([
            $suhu->id => ['baku_mutu' => '38 °C'],
            $bod->id => ['baku_mutu' => '60'],
            $cod->id => ['baku_mutu' => '150'],
            $tss->id => ['baku_mutu' => '50'],
            $fenol->id => ['baku_mutu' => '0.5'],
            $khromTotal->id => ['baku_mutu' => '1.0'],
            $amonia->id => ['baku_mutu' => '8.0'],
            $sulfida->id => ['baku_mutu' => '0.3'],
            $minyakLemak->id => ['baku_mutu' => '3.0'],
            $ph->id => ['baku_mutu' => '6.0 - 9.0'],
        ]);

        $domestik->parameter()->syncWithoutDetaching([
            $bod->id => ['baku_mutu' => '30'],
            $cod->id => ['baku_mutu' => '100'],
            $tss->id => ['baku_mutu' => '30'],
            $minyakLemak->id => ['baku_mutu' => '5'],
            $ph->id => ['baku_mutu' => '6.0 - 9.0'],
            $amonia->id => ['baku_mutu' => '10'],
            $totalColiform->id => ['baku_mutu' => '3000'],
            $debitMaksimum->id => ['baku_mutu' => '100'], // Assuming 'Debit' refers to Debit Maksimum
        ]);

        $makananSpesifik->subKategori()->syncWithoutDetaching([
            $mie->id,
            $kopi->id,
            $permen->id,
            $bumbuMie->id,
            $makananKecil->id,
        ]);

        $pelapisanLogam->parameter()->syncWithoutDetaching([
            $tss->id => ['baku_mutu' => '20'],
            $sianida->id => ['baku_mutu' => '0.2'],
            $khromTotal->id => ['baku_mutu' => '0.5'],
            $khromHexavalen->id => ['baku_mutu' => '0.1'],
            $tembaga->id => ['baku_mutu' => '0.6'],
            $seng->id => ['baku_mutu' => '1.0'],
            $nikel->id => ['baku_mutu' => '1.0'],
            $kadmium->id => ['baku_mutu' => '0.05'],
            $timbal->id => ['baku_mutu' => '0.1'],
            $ph->id => ['baku_mutu' => '6.0 - 9.0'],
            $debitMaksimum->id => ['baku_mutu' => '20 L/ kg bahan pelapis'],
        ]);

        // $belumDitetapkanBakuMutunya->subKategori()->syncWithoutDetaching([
        //     $golonganI->id,
        //     $golonganII->id,
        // ]);

        // $airLindi->parameter()->syncWithoutDetaching([
        //     $ph->id => ['baku_mutu' => '6-9'],
        //     $bod->id => ['baku_mutu' => '150'],
        //     $cod->id => ['baku_mutu' => '300'],
        //     $tss->id => ['baku_mutu' => '100'],
        //     $amonia->id => ['baku_mutu' => '60'],
        //     $merkuri->id => ['baku_mutu' => '0.005'],
        //     $kadmium->id => ['baku_mutu' => '0.1'],
        // ]);

        $tahuTempe->subKategori()->syncWithoutDetaching([
            $industriTahu->id,
            $industriTempe->id,
        ]);

        // Attach parameters for Industri Tahu to its subcategory
        $industriTahu->parameter()->syncWithoutDetaching([
            $suhu->id => ['baku_mutu' => '38'],
            $bod->id => ['baku_mutu' => '150'],
            $cod->id => ['baku_mutu' => '275'],
            $tss->id => ['baku_mutu' => '100'],
            $ph->id => ['baku_mutu' => '6.0 - 9.0'],
            $debitMaksimum->id => ['baku_mutu' => '20 m³/ ton kedelai'],
        ]);

        // Attach parameters for Industri Tempe to its subcategory
        $industriTempe->parameter()->syncWithoutDetaching([
            $suhu->id => ['baku_mutu' => '38'],
            $bod->id => ['baku_mutu' => '150'],
            $cod->id => ['baku_mutu' => '275'],
            $tss->id => ['baku_mutu' => '100'],
            $ph->id => ['baku_mutu' => '6.0 - 9.0'],
            $debitMaksimum->id => ['baku_mutu' => '10 m³/ ton kedelai'],
        ]);

        // $sungai->subKategori()->syncWithoutDetaching([
        //     $kelas1->id,
        //     $kelas2->id,
        //     $kelas3->id,
        //     $kelas4->id,
        // ]);

        // $kayuLapis->parameter()->syncWithoutDetaching([
        //     $ph->id => ['baku_mutu' => '6-9'],
        //     $bod->id => ['baku_mutu' => '75'],
        //     $cod->id => ['baku_mutu' => '125'],
        //     $tss->id => ['baku_mutu' => '50'],
        //     $amonia->id => ['baku_mutu' => '4'],
        //     $fenol->id => ['baku_mutu' => '0.25'],
        //     $debitMaksimum->id => ['baku_mutu' => '0.3 m³ / m³ Produk Kayu Lapis'],
        // ]);
    }
}
