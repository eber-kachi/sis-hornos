<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Exception;
use App\Http\Controllers\Api\Controller;
use App\Models\AsignacionLote;
use App\Models\LoteProduccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AsignacionLotesController extends Controller
{
   /* public function index()
    {
        $asignacion_lote = AsignacionLote::orderBy('id', 'desc')->get();

        $data = $asignacion_lote->transform(function ($asignacion_lote) {
            return $this->transform($asignacion_lote);
        });

        return $this->successResponse(
            'AsignacionLote were successfully retrieved.',
            $data
           // $asignacion_lote
        );

    }  */

    public function index() {
        $rol = auth()->user()->rol->name; // Obtener el nombre del rol del usuario autenticado
        $grupo = auth()->user()->personales->first()->GruposTrabajo; // Obtener el grupo de trabajo del usuario autenticado
        $asignacion_lote = AsignacionLote::when(in_array($rol, ['jefe de contratos', 'ayudante  experto', 'ayudante']),
        function ($query) use ($grupo) {
            return $query->whereHas('GruposTrabajo', function ($query) use ($grupo) {
                $query->where('id', $grupo->id);
            }); })->orderBy('id', 'desc')->get(); // Obtener la asignación que cumple la condición

        $data = $asignacion_lote->transform(function ($asignacion_lote) {
            return $this->transform($asignacion_lote);
        });

        return $this->successResponse(
            'AsignacionLote were successfully retrieved.',
            $data
        // $asignacion_lote
        );

    }
    public function asignacionlote($id_lote)
    {
        $asignaciones = AsignacionLote::where('lote_produccion_id', $id_lote)->get();
       // Esto te devolverá una colección de objetos de tipo AsignacionLote que puedes recorrer con un bucle2.

        $data = $asignaciones->transform(function ($asignaciones) {
            return $this->transform($asignaciones);
        });
        return $this->successResponse(
            'AsignacionLote ',
            $data,
        );

    }

    public function store(Request $request)
    {
        try {
            $validator = $this->getValidator($request);

            if ($validator->fails()) {
                return $this->errorResponse($validator->errors()->all());
            }

            $data = $this->getData($request);


            $asignacion_lote = AsignacionLote::create($data);

            return $this->successResponse(
                'Asignacion Lotes was successfully added.',
                $this->transform($asignacion_lote)
            );
        } catch (Exception $exception) {
            return $this->errorResponse('Unexpected error occurred while trying to process your request.');
        }
    }

    /**
     * Display the specified AsignacionLotes.
     *
     * @param int $id
     *
     * @return Illuminate\Http\Response
     */
    public function show($id)
    {
        $asignacion_lote = AsignacionLote::findOrFail($id);

        return $this->successResponse(
            'Asignacion Lotes was successfully retrieved.',
            $this->transform($asignacion_lote)
        );
    }

    /**
     * Update the specified AsignacionLotes in the storage.
     *
     * @param int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        try {
            $validator = $this->getValidator($request);

            if ($validator->fails()) {
                return $this->errorResponse($validator->errors()->all());
            }

            $data = $this->getData($request);

            $asignacion_lote = AsignacionLote::findOrFail($id);
            $asignacion_lote->update($data);

            return $this->successResponse(
                'Asignacion Lotes was successfully updated.',
                $this->transform($asignacion_lote)
            );
        } catch (Exception $exception) {
            return $this->errorResponse('Unexpected error occurred while trying to process your request.');
        }
    }

    /**successResponse
     * Remove the specified AsignacionLotes from the storage.
     *
     * @param int $id
     *
     * @return Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $asignacion_lote = AsignacionLote::findOrFail($id);
            $asignacion_lote->delete();

            return $this->successResponse(
                'AsignacionLote was successfully deleted.',
                $this->transform($asignacion_lote)
            );
        } catch (Exception $exception) {
            return $this->errorResponse('Unexpected error occurred while trying to process your request.');
        }
    }

    /**
     * Gets a new validator instance with the defined rules.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Support\Facades\Validator
     */
    protected function getValidator(Request $request)
    {
        $rules = [
            "lote_produccion_id" => "required",
            "grupos_trabajo_id" => "required",
            "cantidad_asignada" => "required",

        ];

        return Validator::make($request->all(), $rules);
    }


    /**
     * Get the request's data from the request.
     *
     * @param Illuminate\Http\Request\Request $request
     * @return array
     */
    protected function getData(Request $request)
    {
        $rules = [
            "lote_produccion_id" => "required",
            "grupos_trabajo_id" => "required",
            "cantidad_asignada" => "required",

        ];


        $data = $request->validate($rules);



        return $data;
    }

    /**
     * Transform the giving AsignacionLotes to public friendly array
     *
     * @param App\Models\AsignacionLote $asignacion_lote
     *
     * @return array
     */
    protected function transform(AsignacionLote $asignacion_lote)
    {

        return [
            'id' => $asignacion_lote->id,
            'lote_produccion_id' => $asignacion_lote->lote_produccion_id,
            'grupos_trabajo_id' => $asignacion_lote->grupos_trabajo_id,
            'cantidad_asignada' => $asignacion_lote->cantidad_asignada,

        ];
    }
}
