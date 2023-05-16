<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Producto extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['costo'=>890,'nombre'=>'Horno Mediano','caracteristicas' => 'horno de 4 charolas ', 'precio_unitario'=>1000],
            ['costo'=>1000,'nombre'=>'Horno Grande','caracteristicas' => 'horno de 4 charolas ', 'precio_unitario'=>1300],


        ];
        DB::table('productos')->insert($data);
    }
}
