<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\RolsController;
use App\Http\Controllers\Api;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
  });

  Route::post('auth/register', [AuthController::class, 'register'])->name('api.auth.register');
  Route::post('auth/login', [AuthController::class, 'login'])->name('api.auth.login');
  Route::post('auth/logout', [AuthController::class, 'logout'])->name('api.auth.logout')
    ->middleware('auth:api');
  Route::get('auth/me', [AuthController::class, 'me'])
    ->middleware('auth:api');


Route::group([
    'prefix' => 'users',
], function () {
    Route::get('/', [UsersController::class, 'index'])
         ->name('api.users.user.index');
    Route::get('/show/{user}',[UsersController::class, 'show'])
         ->name('api.users.user.show');
    Route::post('/', [UsersController::class, 'store'])
         ->name('api.users.user.store');
    Route::put('user/{user}', [UsersController::class, 'update'])
         ->name('api.users.user.update');
    Route::delete('/user/{user}',[UsersController::class, 'destroy'])
         ->name('api.users.user.destroy');
});

Route::group([
    'prefix' => 'rols',
], function () {
    Route::get('/', [RolsController::class, 'index'])
         ->name('api.rols.rol.index');
    Route::get('/show/{rol}',[RolsController::class, 'show'])
         ->name('api.rols.rol.show');
    Route::post('/', [RolsController::class, 'store'])
         ->name('api.rols.rol.store');
    Route::put('rol/{rol}', [RolsController::class, 'update'])
         ->name('api.rols.rol.update');
    Route::delete('/rol/{rol}',[RolsController::class, 'destroy'])
         ->name('api.rols.rol.destroy');
});
