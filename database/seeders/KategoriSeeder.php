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
        // $kayuLapis = Kategori::firstOrCreate(['nama' => 'Baku Mutu Industri Kayu Lapis Berdasarkan Permen LHK No.5 Tahun 2014', 'harga' => 490000]);

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
            $bihun->uuid,
            $soun->uuid,
        ]);

        $industriCatTinta->parameter()->syncWithoutDetaching([
            $bod->uuid => ['baku_mutu' => '80'],
            $cod->uuid => ['baku_mutu' => '150'],
            $tss->uuid => ['baku_mutu' => '50'],
            $merkuri->uuid => ['baku_mutu' => '0.01'],
            $seng->uuid => ['baku_mutu' => '1.0'],
            $timbal->uuid => ['baku_mutu' => '0.30'],
            $tembaga->uuid => ['baku_mutu' => '0.80'],
            $khromHexavalen->uuid => ['baku_mutu' => '0.20'],
            $titanium->uuid => ['baku_mutu' => '0.40'],
            $kadmium->uuid => ['baku_mutu' => '0.08'],
            $fenol->uuid => ['baku_mutu' => '0.20'],
            $minyakLemak->uuid => ['baku_mutu' => '10'],
            $ph->uuid => ['baku_mutu' => '6.0 - 9.0'],
            $debitMaksimum->uuid => ['baku_mutu' => '0.5 liter per liter produk cat water base, Zero discharge untuk cat solvent base'],
        ]);

        $industriFarmasi->subKategori()->syncWithoutDetaching([
            $bahanFormula->uuid,
            $formulasi->uuid,
        ]);

        $industriJamu->parameter()->syncWithoutDetaching([
            $bod->uuid => ['baku_mutu' => '60'],
            $cod->uuid => ['baku_mutu' => '120'],
            $tss->uuid => ['baku_mutu' => '60'],
            $fenol->uuid => ['baku_mutu' => '0.2'],
            $ph->uuid => ['baku_mutu' => '6.0 - 9.0'],
            $debitMaksimum->uuid => ['baku_mutu' => '3/ton bahan baku'],
        ]);

         $industriKaret->parameter()->syncWithoutDetaching([
            $bod->uuid => ['baku_mutu' => '150'],
            $cod->uuid => ['baku_mutu' => '300'],
            $tss->uuid => ['baku_mutu' => '150'],
            $amonia->uuid => ['baku_mutu' => '10'],
            $ph->uuid => ['baku_mutu' => '6.0 - 9.0'],
            $debitMaksimum->uuid => ['baku_mutu' => '40 m³/ton produk karet'],
        ]);

        $industriTekstilBatik->parameter()->syncWithoutDetaching([
            $suhu->uuid => ['baku_mutu' => '38 °C'],
            $bod->uuid => ['baku_mutu' => '60'],
            $cod->uuid => ['baku_mutu' => '150'],
            $tss->uuid => ['baku_mutu' => '50'],
            $fenol->uuid => ['baku_mutu' => '0.5'],
            $khromTotal->uuid => ['baku_mutu' => '1.0'],
            $amonia->uuid => ['baku_mutu' => '8.0'],
            $sulfida->uuid => ['baku_mutu' => '0.3'],
            $minyakLemak->uuid => ['baku_mutu' => '3.0'],
            $ph->uuid => ['baku_mutu' => '6.0 - 9.0'],
        ]);

        $domestik->parameter()->syncWithoutDetaching([
            $bod->uuid => ['baku_mutu' => '30'],
            $cod->uuid => ['baku_mutu' => '100'],
            $tss->uuid => ['baku_mutu' => '30'],
            $minyakLemak->uuid => ['baku_mutu' => '5'],
            $ph->uuid => ['baku_mutu' => '6.0 - 9.0'],
            $amonia->uuid => ['baku_mutu' => '10'],
            $totalColiform->uuid => ['baku_mutu' => '3000'],
            $debitMaksimum->uuid => ['baku_mutu' => '100'], // Assuming 'Debit' refers to Debit Maksimum
        ]);

        $makananSpesifik->subKategori()->syncWithoutDetaching([
            $mie->uuid,
            $kopi->uuid,
            $permen->uuid,
            $bumbuMie->uuid,
            $makananKecil->uuid,
        ]);

        $pelapisanLogam->parameter()->syncWithoutDetaching([
            $tss->uuid => ['baku_mutu' => '20'],
            $sianida->uuid => ['baku_mutu' => '0.2'],
            $khromTotal->uuid => ['baku_mutu' => '0.5'],
            $khromHexavalen->uuid => ['baku_mutu' => '0.1'],
            $tembaga->uuid => ['baku_mutu' => '0.6'],
            $seng->uuid => ['baku_mutu' => '1.0'],
            $nikel->uuid => ['baku_mutu' => '1.0'],
            $kadmium->uuid => ['baku_mutu' => '0.05'],
            $timbal->uuid => ['baku_mutu' => '0.1'],
            $ph->uuid => ['baku_mutu' => '6.0 - 9.0'],
            $debitMaksimum->uuid => ['baku_mutu' => '20 L/ kg bahan pelapis'],
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
            $industriTahu->uuid,
            $industriTempe->uuid,
        ]);

        // Attach parameters for Industri Tahu to its subcategory
        $industriTahu->parameter()->syncWithoutDetaching([
            $suhu->uuid => ['baku_mutu' => '38'],
            $bod->uuid => ['baku_mutu' => '150'],
            $cod->uuid => ['baku_mutu' => '275'],
            $tss->uuid => ['baku_mutu' => '100'],
            $ph->uuid => ['baku_mutu' => '6.0 - 9.0'],
            $debitMaksimum->uuid => ['baku_mutu' => '20 m³/ ton kedelai'],
        ]);

        // Attach parameters for Industri Tempe to its subcategory
        $industriTempe->parameter()->syncWithoutDetaching([
            $suhu->uuid => ['baku_mutu' => '38'],
            $bod->uuid => ['baku_mutu' => '150'],
            $cod->uuid => ['baku_mutu' => '275'],
            $tss->uuid => ['baku_mutu' => '100'],
            $ph->uuid => ['baku_mutu' => '6.0 - 9.0'],
            $debitMaksimum->uuid => ['baku_mutu' => '10 m³/ ton kedelai'],
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
