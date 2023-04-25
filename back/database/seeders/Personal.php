<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Personal extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

            DB::table('personals')->insert([
                'nombres' => 'Juan Sebastian',
                'apellidos'=>'Calle Vidal',
                'carnet_identidad'=> '12344-21',
                'fecha_nacimiento'=> '1987-12-12',
               'fecha_registro'=> '1987-12-12',
               'direccion'=> 'San Martin 123',
               'user_id'=>1

                ]);




    }
}
