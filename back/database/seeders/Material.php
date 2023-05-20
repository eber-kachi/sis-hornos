<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Material extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $data = [
            ['nombre'=>'Jefe Contrato','display_name' => 'Jefe Contratos', 'enabled'=>1],
            ['id'=>7,'name'=>'Ayudante Experto','display_name' => 'Ayudante Experto', 'enabled'=>1],
            ['id'=>8,'name'=>'Ayudante','display_name' => 'Ayudante', 'enabled'=>1]

        ];
        DB::table('rols')->insert($data);
    }
}
