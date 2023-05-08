<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GruposTrabajo extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('grupos_trabajos')->insert([
            'nombre'=>'Grupo 1',
            'cantidad_integrantes'=> 3,
            'tipo_grupo_id'=> 1

        ], [
            'nombre' => 'Grupo 1',
            'cantidad_integrantes' => 3,
            'tipo_grupo_id' => 1

        ], [
            'nombre' => 'Grupo 1',
            'cantidad_integrantes' => 3,
            'tipo_grupo_id' => 1

        ]);
    }
}
