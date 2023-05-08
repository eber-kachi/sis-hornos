<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class tipoGrupo extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipo_grupos')->insert([

        'nombre'=>'Expertos',
        'cantidad_produccion_diaria'=> 3,
    ], [

            'nombre' => 'Expertos',
            'cantidad_produccion_diaria' => 3,
        ], [

            'nombre' => 'Expertos',
            'cantidad_produccion_diaria' => 3,
        ]);

    }
}
