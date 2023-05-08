<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Departamento extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nombre'=>'La paz' ],
            ['nombre' => 'Santa Cruz'],
            ['nombre' => 'Cochabamba'],
            ['nombre' => 'Potosi'],
            ['nombre' => 'Oruro'],
            ['nombre' => 'Beni'],
            ['nombre' => 'Pando'],
            ['nombre' => 'Tarija'],
            ['nombre' => 'Chuquisaca']
        ];
        DB::table('medidas')->insert($data);
    }
}
