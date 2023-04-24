<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TipoGrupo;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TipoGrupoController extends Controller
{
    
    public function index()
    {
        
        $TipoGrupos = TipoGrupo::all();
        return response()->json([
            "results" => $TipoGrupos
        ], Response::HTTP_OK);

        
    }

    
    

    public function store(Request $request)
    {
        
        //validamos los datos
        $request->validate([
            "nombre" => "required|string",
            "cantidadProduccionDiaria" => "required|numeric|min:0"
        ]);
        //damos de alta en la DB
        $TipoGrupo = TipoGrupo::create([
            "nombre" => $request->nombre,
            "cantidadProduccionDiaria" => $request->cantidadProduccionDiaria
        ]);
        //devolvemos una rpta
        return response()->json([
            "result" => $TipoGrupo
        ], Response::HTTP_OK);
        //
    }

    public function show($id)
    {
       $tipoGrupo = TipoGrupo::find($id);
        return response()->json([
            "result" => $tipoGrupo->nombre()
        ], Response::HTTP_OK);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            "nombre" => "required|string",
            "cantidadProduccionDiaria" => "required|numeric|min:0"
        ]);
         //actualizamos en la DB
         $TipoGrupo = TipoGrupo::find($id);
         $TipoGrupo->nombre = $request->nombre;
         $TipoGrupo->cantidadProduccionDiaria = $request->cantidadProduccionDiaria;
         $TipoGrupo->save();
         //devolvemos una rpta
         return response()->json([
             "result" => $TipoGrupo
         ], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        TipoGrupo::findOrFail($id)->delete();
        //devolvemos una rpta
        return response()->json([
            "result" => "TipoGrupo deleted"
        ], Response::HTTP_OK);
        
    }
}
