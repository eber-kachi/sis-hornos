<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Rol extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['id'=>6,'name'=>'Jefe Contratos','display_name' => 'Jefe Contratos', 'enabled'=>1],
            ['id'=>7,'name'=>'Ayudante Experto','display_name' => 'Ayudante Experto', 'enabled'=>1],
            ['id'=>8,'name'=>'Ayudante','display_name' => 'Ayudante', 'enabled'=>1]

        ];
        DB::table('rols')->insert($data);
    }
}
