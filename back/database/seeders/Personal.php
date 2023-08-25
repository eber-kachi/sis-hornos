<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Personal extends Seeder
{
    public function run(): void
    {

        $usuarios = [
            ['id'=>1,'name' => 'Fernando', 'email' => 'Fernando@megahornosrojas.com', 'username' => '12461-23',
                'enabled'=> 1,'password'=>Hash::make('megahornosrojas'),'remember_token'=>Str::random(10),'rol_id'=>5 ],
            ['id'=>2,'name' => 'Carlos', 'email' => 'carlos@megahornosrojas.com', 'username' => '12461-23',
                'enabled'=> 1,'password'=>Hash::make('megahornosrojas'),'remember_token'=>Str::random(10),'rol_id'=>6 ],
            ['id'=>3,'name' => 'Pedro', 'email' => 'pedro@megahornosrojas.com', 'username' => '12461-23',
                'enabled'=> 1,'password'=>Hash::make('megahornosrojas'),'remember_token'=>Str::random(10),'rol_id'=>7 ],
            ['id'=>4,'name' => 'Franco', 'email' => 'franco@megahornosrojas.com', 'username' => '12461-23',
                'enabled'=> 1,'password'=>Hash::make('megahornosrojas'),'remember_token'=>Str::random(10),'rol_id'=>8 ],

            // Agrega más tipos aquí
        ];
        // Bucle para insertar cada personals de grupo en la tabla
        foreach ($usuarios as $usuario) {
            DB::table('users')->insert($usuario);
        }


        // Array con los datos de los personals de grupo
        $personales = [
            ['nombres' => 'Fernando  ', 'apellidos' => 'Rojas  Medrano', 'carnet_identidad' => '12461-23',
                'fecha_nacimiento'=> '1987-12-12','fecha_registro'=> '2023-05-12','direccion'=> 'San Martin 342','user_id'=>1 ],
            ['nombres' => 'Pedro Ferbando', 'apellidos' => 'Pelaes Rocha', 'carnet_identidad' => '124231-15',
                'fecha_nacimiento'=> '1987-05-23','fecha_registro'=> '2023-05-12','direccion'=> 'San Martin 342','user_id'=>2 ],
            ['nombres' => 'Fernando', 'apellidos' => 'Escobar Condori', 'carnet_identidad' => '12461-23',
                'fecha_nacimiento'=> '1987-12-12','fecha_registro'=> '2023-05-12','direccion'=> 'San Martin 342','user_id'=>3 ],
            ['nombres' => 'Franco', 'apellidos' => 'Mamani Rocha', 'carnet_identidad' => '12461-23',
                'fecha_nacimiento'=> '1987-12-12','fecha_registro'=> '2023-05-12','direccion'=> 'San Martin 342','user_id'=>4 ]
            // Agrega más tipos aquí
        ];

        // Bucle para insertar cada personals de grupo en la tabla
        foreach ($personales as $personal) {
            DB::table('personals')->insert($personal);
        }






    }
}
