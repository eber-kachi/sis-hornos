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
        // Array con los datos de los tipos de grupo
        $tipos = [
            ['nombre' => 'G2', 'cantidad_produccion_diaria' => 2.5],
            ['nombre' => 'G3', 'cantidad_produccion_diaria' => 3],
            ['nombre' => 'G4', 'cantidad_produccion_diaria' => 3.5],
            ['nombre' => 'G5', 'cantidad_produccion_diaria' => 4],
            // Agrega más tipos aquí
        ];

        // Bucle para insertar cada tipo de grupo en la tabla
        foreach ($tipos as $tipo) {
            DB::table('tipo_grupos')->insert($tipo);
        }
    }
}
