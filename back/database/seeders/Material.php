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
            ['nombre'=>'Plancha Negra 0.45','medida_id' => 5, 'caracteristica'=>'largo 200 cm  x ancho 100 cm '],
            ['nombre'=>'Plancha Negra 0.40','medida_id' => 5, 'caracteristica'=>'largo 200 cm  x ancho 100 cm '],
            ['nombre'=>'Angular 3/4','medida_id' =>2, 'caracteristica'=>'largo 600 cm '],
            ['nombre'=>'Fierro Estriado 5/16','medida_id' =>2,'caracteristica'=>'largo 1200 cm'],
            ['nombre'=>'Platino 1/2','medida_id' => 2, 'caracteristica'=>'largo 600 cm '],
            ['nombre'=>'Tubo 19','medida_id' =>2, 'caracteristica'=>'largo 600 cm '],
            ['nombre'=>'Tubo 16','medida_id' =>2, 'caracteristica'=>'largo 600 cm '],
            ['nombre'=>'Tubo 50','medida_id' =>2, 'caracteristica'=>'largo 600 cm '],
            ['nombre'=>'Tubo Cuadrado','medida_id' =>2, 'caracteristica'=>'largo 600 cm '],
            ['nombre'=>'Bronce Barra','medida_id' =>4, 'caracteristica'=>'largo 200 cm '],
            ['nombre'=>'Pintura','medida_id' =>1, 'caracteristica'=>'1 kg'],
            ['nombre'=>'LLave','medida_id' =>4, 'caracteristica'=>'Accesorio'],
            ['nombre'=>'Vidrio','medida_id' =>4,'caracteristica'=>'Accesorio'],
            ['nombre'=>'Tornillos','medida_id' =>4, 'caracteristica'=>'Accesorio'],
            ['nombre'=>'Ladrillos','medida_id' =>4, 'caracteristica'=>'Accesorio'],
            ['nombre'=>'Manguera','medida_id' =>4, 'caracteristica'=>'Accesorio'],
            ['nombre'=>'Reloj','medida_id' =>4, 'caracteristica'=>'Accesorio'],
            ['nombre'=>'Visagra','medida_id' =>4, 'caracteristica'=>'Accesorio'],
            ['nombre'=>'Picaporte','medida_id' =>4, 'caracteristica'=>'Accesorio'],
            ['nombre'=>'Mariposa','medida_id' =>4, 'caracteristica'=>'Accesorio'],
            ['nombre'=>'Abrasadera','medida_id' =>4, 'caracteristica'=>'Accesorio'],
            ['nombre'=>'Pirulo','medida_id' =>4, 'caracteristica'=>'Accesorio'],







        ];
        DB::table('materials')->insert($data);
    }
}
