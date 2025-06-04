<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ParameterUji;

class ParameterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $parameters = [
            // FISIKA
            [
                'nama_parameter' => 'Suhu',
                'satuan' => '°C',
                'harga' => 10000
            ],
            [
                'nama_parameter' => 'Ph',
                'satuan' => '-',
                'harga' => 15000
            ],
            [
                'nama_parameter' => 'DHL',
                'satuan' => 'µS/cm',
                'harga' => 15000
            ],
            [
                'nama_parameter' => 'TSS',
                'satuan' => 'mg/L',
                'harga' => 30000
            ],
            [
                'nama_parameter' => 'TDS',
                'satuan' => 'mg/L',
                'harga' => 10000
            ],
            [
                'nama_parameter' => 'Kekeruhan',
                'satuan' => 'NTU',
                'harga' => 10000
            ],
            [
                'nama_parameter' => 'Salinitas',
                'satuan' => '‰',
                'harga' => 10000
            ],
            [
                'nama_parameter' => 'Warna',
                'satuan' => 'TCU',
                'harga' => 20000
            ],

            // KIMIA
            [
                'nama_parameter' => 'COD',
                'satuan' => 'mg/L',
                'harga' => 100000
            ],
            [
                'nama_parameter' => 'BOD',
                'satuan' => 'mg/L',
                'harga' => 100000
            ],
            [
                'nama_parameter' => 'Phosphat',
                'satuan' => 'mg/L',
                'harga' => 50000
            ],
            [
                'nama_parameter' => 'Nitrat',
                'satuan' => 'mg/L',
                'harga' => 50000
            ],
            [
                'nama_parameter' => 'Nitrit',
                'satuan' => 'mg/L',
                'harga' => 50000
            ],
            [
                'nama_parameter' => 'MBAS Detergent',
                'satuan' => 'mg/L',
                'harga' => 50000
            ],
            [
                'nama_parameter' => 'Minyak Lemak',
                'satuan' => 'mg/L',
                'harga' => 100000
            ],
            [
                'nama_parameter' => 'Minyak Nabati',
                'satuan' => 'mg/L',
                'harga' => 100000
            ],
            [
                'nama_parameter' => 'Minyak Mineral',
                'satuan' => 'mg/L',
                'harga' => 100000
            ],
            [
                'nama_parameter' => 'Fenol',
                'satuan' => 'mg/L',
                'harga' => 150000
            ],
            [
                'nama_parameter' => 'NH3-N',
                'satuan' => 'mg/L',
                'harga' => 100000
            ],
            [
                'nama_parameter' => 'DO',
                'satuan' => 'mg/L',
                'harga' => 25000
            ],
            [
                'nama_parameter' => 'Sulfida',
                'satuan' => 'mg/L',
                'harga' => 75000
            ],
            [
                'nama_parameter' => 'Total N',
                'satuan' => 'mg/L',
                'harga' => 120000
            ],
            [
                'nama_parameter' => 'Sulfat',
                'satuan' => 'mg/L',
                'harga' => 40000
            ],
            [
                'nama_parameter' => 'Krom Heksavalen',
                'satuan' => 'mg/L',
                'harga' => 75000
            ],
            [
                'nama_parameter' => 'Fe (Besi)',
                'satuan' => 'mg/L',
                'harga' => 75000
            ],
            [
                'nama_parameter' => 'Mn (Mangan)',
                'satuan' => 'mg/L',
                'harga' => 75000
            ],
            [
                'nama_parameter' => 'Cu (Tembaga)',
                'satuan' => 'mg/L',
                'harga' => 75000
            ],
            [
                'nama_parameter' => 'Cr (Krom Total)',
                'satuan' => 'mg/L',
                'harga' => 75000
            ],
            [
                'nama_parameter' => 'Cd (Kadmium)',
                'satuan' => 'mg/L',
                'harga' => 75000
            ],
            [
                'nama_parameter' => 'Pb (Timbal)',
                'satuan' => 'mg/L',
                'harga' => 75000
            ],
            [
                'nama_parameter' => 'Zn (seng)',
                'satuan' => 'mg/L',
                'harga' => 75000
            ],
            [
                'nama_parameter' => 'Hg (Hidroksipropa / Merkuri)',
                'satuan' => 'mg/L',
                'harga' => 75000
            ],
            [
                'nama_parameter' => 'Al (Alumunium)',
                'satuan' => 'mg/L',
                'harga' => 75000
            ],
            [
                'nama_parameter' => 'As (Arsenikum)',
                'satuan' => 'mg/L',
                'harga' => 75000
            ],
            [
                'nama_parameter' => 'Mg (Magnesium)',
                'satuan' => 'mg/L',
                'harga' => 75000
            ],
            [
                'nama_parameter' => 'Ni (Nikel)',
                'satuan' => 'mg/L',
                'harga' => 75000
            ],
            [
                'nama_parameter' => 'Ag (Argentum / Perak)',
                'satuan' => 'mg/L',
                'harga' => 75000
            ],
            [
                'nama_parameter' => 'Ba (Barium)',
                'satuan' => 'mg/L',
                'harga' => 75000
            ],
            [
                'nama_parameter' => 'Se (Selenium)',
                'satuan' => 'mg/L',
                'harga' => 75000
            ],
            [
                'nama_parameter' => 'Co (Kobalt)',
                'satuan' => 'mg/L',
                'harga' => 75000
            ],
            [
                'nama_parameter' => 'Sn (Timah)',
                'satuan' => 'mg/L',
                'harga' => 75000
            ],
            [
                'nama_parameter' => 'Cn (Sianida)',
                'satuan' => 'mg/L',
                'harga' => 75000
            ],
            [
                'nama_parameter' => 'H2s (Sulfida)',
                'satuan' => 'mg/L',
                'harga' => 75000
            ],
            [
                'nama_parameter' => 'F (Flourida)',
                'satuan' => 'mg/L',
                'harga' => 75000
            ],
            [
                'nama_parameter' => 'Cl2 (Klorin Bebas)',
                'satuan' => 'mg/L',
                'harga' => 75000
            ],
            [
                'nama_parameter' => 'Ti (Titanium)',
                'satuan' => 'mg/L',
                'harga' => 75000
            ],
            [
                'nama_parameter' => 'Logam Lainnya',
                'satuan' => 'mg/L',
                'harga' => 75000
            ],

            // BIOLOGI
            [
                'nama_parameter' => 'Total Coliform',
                'satuan' => 'CFU/100mL',
                'harga' => 75000
            ],
            [
                'nama_parameter' => 'Fecal Coliform',
                'satuan' => 'CFU/100mL',
                'harga' => 75000
            ],
            [
                'nama_parameter' => 'Debit Maksimum',
                'satuan' => 'm³/ton',
                'harga' => 0
            ],
            [
                'nama_parameter' => 'Radio Aktifitas',
                'satuan' => '-',
                'harga' => 0
            ]
        ];

        foreach ($parameters as $parameter) {
            ParameterUji::create($parameter);
        }
    }
}
