<?php


use App\Http\Controllers\Api\AsignacionLotesController;
use App\Http\Controllers\Api\ClientesController;
use App\Http\Controllers\Api\DepartamentosController;
use App\Http\Controllers\Api\ProductosController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\RolsController;
use App\Http\Controllers\Api\TipoGrupoController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GruposTrabajoController;
use App\Http\Controllers\Api\LoteProduccionController;
use App\Http\Controllers\Api\MaterialesController;
use App\Http\Controllers\Api\MaterialProductosController;
use App\Http\Controllers\Api\MedidasController;
use App\Http\Controllers\Api\PedidosController;
use App\Http\Controllers\Api\PersonalController;

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
    Route::get('/{rol}',[RolsController::class, 'show'])
         ->name('api.rols.rol.show');
    Route::post('/', [RolsController::class, 'store'])
         ->name('api.rols.rol.store');
    Route::put('/{rol}', [RolsController::class, 'update'])
         ->name('api.rols.rol.update');
    Route::delete('/{rol}',[RolsController::class, 'destroy'])
         ->name('api.rols.rol.destroy');
});

//Route::group(['middleware' => 'auth:api'], function(){

Route::resource('/tipo_grupos', TipoGrupoController::class, ['update','destroy','show','index'.'store']);
Route::resource('/grupos_trabajo', GruposTrabajoController::class, ['update','destroy','show','index'.'store']);

Route::resource('/personal', PersonalController::class, ['update','destroy','show','index'.'store']);
Route::get('/personal/lista/sin-grupo', [PersonalController::class,'singrupo']);
Route::get('/personal/lista/jefe', [PersonalController::class,'personalSinGrupoJefe']);
Route::get('/personal/lista/nojefe', [PersonalController::class,'personalsingruponojefe']);

Route::resource('/materiales', MaterialesController::class, ['update','destroy','show','index'.'store']);
Route::resource('/productos', ProductosController::class, ['update','destroy','show','index'.'store']);

Route::resource('/materiales_productos', MaterialProductosController::class, ['update','destroy','show','index'.'store']);


Route::get('/index_producto_material',[MaterialProductosController::class,'indexProductoMaterial']);
Route::resource('/cliente', ClientesController::class, ['update','destroy','show','index'.'store']);
Route::resource('/departamentos', DepartamentosController::class, ['update','destroy','show','index'.'store']);
Route::resource('/asignacion_lotes', AsignacionLotesController::class, ['update','destroy','show','index'.'store']);
Route::resource('/lotes_produccion', LoteProduccionController::class, ['update','destroy','show','index'.'store']);

Route::resource('/pedidos', PedidosController::class, ['update','destroy','show','index'.'store']);
Route::get('/pedidos/lista/activos/{producto_id}', [PedidosController::class,'listarPedidosActivosPorProducto']);

Route::resource('/medidas', MedidasController::class, ['update','destroy','show','index'.'store']);
Route::get('/lote', [LoteProduccionController::class,'agregar']);


//});




