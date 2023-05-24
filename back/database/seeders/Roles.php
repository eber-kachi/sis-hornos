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
            ['id'=>6,'name'=>'jefe contratos','display_name' => 'Jefe Contratos', 'enabled'=>1],
            ['id'=>7,'name'=>'ayudante  experto','display_name' => 'Ayudante Experto', 'enabled'=>1],
            ['id'=>8,'name'=>'ayudante','display_name' => 'Ayudante', 'enabled'=>1]

        ];
        DB::table('rols')->insert($data);
    }
}
