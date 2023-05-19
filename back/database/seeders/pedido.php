<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class pedido extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nombre'=>'(Kg)Kilogramos' ],
            ['nombre' => '(Cm)Centimetros'],
            ['nombre' => '(m)Metros'],
            ['nombre' => '(U)Unidad'],
            ['nombre' => '(Cm2) Centimetros cuadrado'],
            ['nombre' => '(L)litros']
        ];
        DB::table('medidas')->insert($data);
    }
}
