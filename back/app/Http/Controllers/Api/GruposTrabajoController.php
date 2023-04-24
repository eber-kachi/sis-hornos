<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GruposTrabajo;
use Illuminate\Http\Request;
use App\Models\TipoGrupo;
use Symfony\Component\HttpFoundation\Response;

class GruposTrabajoController extends Controller
{
    public function index()
    {
        $gruposTrabajos = GruposTrabajo::all();        
        return response()->json([
            "results" => $gruposTrabajos
        ], Response::HTTP_OK);
    }    
    
    public function store(Request $request)
    {
        // validamos los datos
        $request->validate([
            "nombre" => "required|string",
            "cantidadIntegrantes" => "required|numeric|min:0",
            "TipoGrupo_id" => "required"
        ]);
        $TipoGrupo = TipoGrupo::findOrFail($request->TipoGrupo_id);
        $gruposTrabajo = $TipoGrupo->GruposTrabajos()->create([
            'nombre' => $request->nombre,
            'cantidadIntegrantes' => $request->cantidadIntegrantes,
        ]);
        //devolvemos una rpta
        return response()->json([
            "result" => $gruposTrabajo
        ], Response::HTTP_OK);
    }

    public function show($id)
    {
       $gruposTrabajo = GruposTrabajo::find($id);
        return response()->json([
            "result" => $gruposTrabajo->categories()
        ], Response::HTTP_OK);
    }

    public function update(Request $request, $gruposTrabajo_id)
    {
        $request->validate([
            "nombre" => "required|string",
            "cantidadIntegrantes" => "required|numeric|min:0",
            "TipoGrupo_id" => "required"
        ]);
        $TipoGrupo = TipoGrupo::findOrFail($request->TipoGrupo_id);
        $gruposTrabajo = $TipoGrupo->GruposTrabajos()->where('id', $gruposTrabajo_id)->update([
            'nombre' => $request->nombre,
            'cantidadIntegrantes' => $request->cantidadIntegrantes,
        ]);
        return response()->json([
            "message" => "¡GruposTrabajo Updated!",
            "result" => $gruposTrabajo
        ], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        GruposTrabajo::findOrFail($id)->delete();
        return response()->json([
            "message" => "¡GruposTrabajo deleted!"            
        ], Response::HTTP_OK);
        
    }
    
}
