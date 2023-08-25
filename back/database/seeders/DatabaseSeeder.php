<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\UserRol;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */


    public function run(): void
    {

        $this->call(Medidas::class);
        $this->call(Departamento::class);
        $this->call(Roles::class);
        $this->call(Material::class);
        $this->call(Producto::class);
        $this->call(Personal::class);



    }
}
