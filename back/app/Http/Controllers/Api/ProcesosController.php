<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Exception;
use App\Http\Controllers\Api\Controller;
use App\Models\Proceso;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
class ProcesosController extends Controller
{




    public function index()
    {
        $procesos = Proceso::orderBy('id', 'desc')->get();

        $data = $procesos->transform(function ($procesos) {
            return $this->transform($procesos);
        });

        return $this->successResponse(
            'Procesos were successfully retrieved.',
            $data
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

            $proceso = Proceso::create($data);

            return $this->successResponse(
                'Procesos was successfully added.',
                $this->transform($proceso)
            );
        } catch (Exception $exception) {
            return $this->errorResponse('Unexpected error occurred while trying to process your request.');
        }
    }

    /**
     * Display the specified $procesos.
     *
     * @param int $id
     *
     * @return Illuminate\Http\Response
     */
    public function show($id)
    {
        $proceso = Proceso::findOrFail($id);

        return $this->successResponse(
            'procesos was successfully retrieved.',
            $this->transform($proceso)
        );
    }

    /**
     * Update the specified $proceso in the storage.
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

            $proceso = Proceso::findOrFail($id);
            $proceso->update($data);

            return $this->successResponse(
                'Grupos Trabajos was successfully updated.',
                $this->transform($proceso)
            );
        } catch (Exception $exception) {
            return $this->errorResponse('Unexpected error occurred while trying to process your request.');
        }
    }

    /**
     * Remove the specified Departamentos from the storage.
     *
     * @param int $id
     *
     * @return Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $proceso = Proceso::findOrFail($id);
            $proceso->delete();

            return $this->successResponse(
                'Departamento was successfully deleted.',
                $this->transform($proceso)
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

            "marcado_planchas" => "required|string",
            "cortado_planchas" => "required|string",
            "plegado_planchas" => "required|string",
            "soldadura" => "required|string",
            "prueba_conductos" => "required|string",
            "armado_cuerpo" => "required|string",
            "pintado" => "required|string",
            "armado_accesorios" => "required|string",

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

            "marcado_planchas" => "required|string",
            "cortado_planchas" => "required|string",
            "plegado_planchas" => "required|string",
            "soldadura" => "required|string",
            "prueba_conductos" => "required|string",
            "armado_cuerpo" => "required|string",
            "pintado" => "required|string",
            "armado_accesorios" => "required|string",

        ];


        $data = $request->validate($rules);

        return $data;
    }

    /**
     * Transform the giving Departamentos to public friendly array
     *
     * @param App\Models\Proceso $proceso
     *
     * @return array
     */
    protected function transform(Proceso $proceso)
    {

        return [
            
                'id' => $proceso->id,
                "marcado_planchas" =>   $proceso->marcado_planchas,
                "cortado_planchas" =>  $proceso->cortado_planchas,
                "plegado_planchas" =>  $proceso->plegado_planchas,
                "soldadura" =>  $proceso->soldadura,
                "prueba_conductos" =>  $proceso->prueba_conductos,
                "armado_cuerpo" =>  $proceso->armado_cuerpo,
                "pintado" =>  $proceso->pintado,
                "armado_accesorios" =>  $proceso->armado_accesorios,

        ];
    }
}
