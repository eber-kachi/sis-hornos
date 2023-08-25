<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Roles extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['id'=>5,'name'=>'administrador','display_name' => 'Administrador', 'enabled'=>1],
            ['id'=>6,'name'=>'jefe de contratos','display_name' => 'Jefe de Contratos', 'enabled'=>1],
            ['id'=>7,'name'=>'ayudante  experto','display_name' => 'Ayudante Experto', 'enabled'=>1],
            ['id'=>8,'name'=>'ayudante','display_name' => 'Ayudante', 'enabled'=>1]

        ];
        DB::table('rols')->insert($data);
    }
}
