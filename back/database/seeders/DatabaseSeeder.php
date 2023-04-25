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
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $rol = new Rol();
    $rol->name = 'admin';
    $rol->display_name = 'Admin';
    $rol->enabled = 1;
    $rol->save();

   $user = new User();
   $user->name = 'admin';
   $user->email = 'admin@gmail.com';
   $user->username = 'admin';
//    $user->email_verified_at = now();
   $user->enabled = 1;
   $user->password = Hash::make('123456789');
   $user->remember_token = Str::random(10);
   // $user->rol_id = 1;
   $user->save();

   UserRol::create([
    "rol_id" => 1,
    "user_id" => 1
  ]);

    }
}
