<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Rol;
use App\Models\Settings;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
  //
  public function register(Request $request)
  {
    $user = new User([
      'email' => $request->email,
      'name' => $request->name,
      'password' => bcrypt($request->password),
    ]);

    $user->save();

    $token = $user->createToken('authToken')->accessToken;

    return response()->json([
      'ok' => true,
      'user' => $user,
      'accessToken' => $token
    ]);
  }

  public function login(Request $request)
  {

//        $data = $request->only('email', 'password');
    $val = '';
    if ($request->email) {
      $val = $request->email;
    }
    if ($request->username) {
      $val = $request->username;
    }

    if (Auth::attempt(['email' => $val, 'password' => $request->password]) || Auth::attempt(['username' => $val, 'password' => $request->password])) {

      $token = Auth::user()->createToken('authToken')->accessToken;

      return response()->json([
        'ok' => true,
        'user' => Auth::user(),
        // 'roles' => User::find(Auth::user()->id)->rols()->get(),
        'roles' => Auth::user()->rol()->get(),
        // 'member' => Auth::user()->member()->get(),
        // 'member' => Auth::user()->member()->with('parcels')->get(),
        // 'member' => Auth::user()->with(['member' => function($q) {
        //   $q->with('parcels');
        // }])->get(),
        // 'setting' => Settings::first(),
        'accessToken' => $token
      ]);
    } else {
      return response()->json([
        'ok' => false,
        'message' => 'Usuario o Contraseña es incorrecto.'
      ], 409);
    }
    // if (! Auth::attempt($data)) {
    //     return response()->json([
    //         'ok' =>false,
    //         'message'=> 'error de credenciales',
    //     ]);
    // }
  }

  public function me()
  {

    return response()->json([
      'ok' => true,
      'user' => Auth::user(),
    'roles' => Auth::user()->rol()->get(),
    //   'accessToken' => Auth::user()->token()->
    ]);
  }

  public function logout(Request $request)
  {

    if (Auth::check()) {
      $token = Auth::user()->token();
      $token->revoke();
    }
    return response()->json([
      'ok' => true,
      'message' => 'Se cerro session correctamente.',
    ]);
  }
}
