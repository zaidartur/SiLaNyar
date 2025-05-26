<?php

namespace Database\Seeders;

use App\Models\JenisCairan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JenisCairanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jeniscairan = [
            [
                'nama' => 'Sampel Total Volume',
                'batas_minimum' =>  2500,
            ],
            [
                'nama' => 'Sampel Minyak Lemak',
                'batas_minimum' =>  1000,
            ],
            [
                'nama' => 'Sampel Vecal Coli',
                'batas_minimum' =>  100,
            ],
        ];

        foreach ($jeniscairan as $jc) {
            JenisCairan::create($jc);
        }
    }
}
