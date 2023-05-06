<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Exception;
use App\Http\Controllers\Api\Controller;
use App\Models\LoteProduccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoteProduccionController extends Controller
{
    
    public function index()
    {
        $lote_produccion = LoteProduccion::paginate(25);

        $data = $lote_produccion->transform(function ($lote_produccion) {
            return $this->transform($lote_produccion);
        });

        return $this->successResponse(
            'Lote Produccion were successfully retrieved.',
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

            $lote_produccion = LoteProduccion::create($data);

            return $this->successResponse(
                'Lote  Produccion was successfully added.',
                $this->transform($lote_produccion)
            );
        } catch (Exception $exception) {
            return $this->errorResponse('Unexpected error occurred while trying to process your request.');
        }
    }

    /**
     * Display the specified Productos.
     *
     * @param int $id
     *
     * @return Illuminate\Http\Response
     */
    public function show($id)
    {
        $lote_produccion = LoteProduccion::findOrFail($id);

        return $this->successResponse(
            'Lote  Produccion was successfully retrieved.',
            $this->transform($lote_produccion)
        );
    }

    /**
     * Update the specified Productos in the storage.
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

            $lote_produccion = LoteProduccion::findOrFail($id);
            $lote_produccion->update($data);

            return $this->successResponse(
                'Lote  Produccion was successfully updated.',
                $this->transform($lote_produccion)
            );
        } catch (Exception $exception) {
            return $this->errorResponse('Unexpected error occurred while trying to process your request.');
        }
    }

    /**
     * Remove the specified Productos from the storage.
     *
     * @param int $id
     *
     * @return Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $lote_produccion = LoteProduccion::findOrFail($id);
            $lote_produccion->delete();

            return $this->successResponse(
                'Materiles was successfully deleted.',
                $this->transform($lote_produccion)
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
            "cantidad" => "required|numeric|min:0",
            "fecha_inicio" => "nullable",
            "fecha_final" => "nullable",
            "activo" => "string",
            "fecha_registro" => "required",
            'enabled' => 'boolean',
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
            "cantidad" => "required|numeric|min:0",
            "fecha_inicio" => "nullable",
            "fecha_final" => "nullable",
            "activo" => "string",
            "fecha_registro" => "required",
            'enabled' => 'boolean',

        ];


        $data = $request->validate($rules);


        $data['enabled'] = $request->has('enabled');


        return $data;
    }

    /**
     * Transform the giving Productos to public friendly array
     *
     * @param App\Models\Productos $lote_produccion
     *
     * @return array
     */
    protected function transform(Producto $lote_produccion)
    {

        return [
            'id' => $lote_produccion->id,
            'cantidad' => $lote_produccion->cantidad,
            'fecha_inicio' => $lote_produccion->fecha_inicio,
            'fecha_final' => $lote_produccion->fecha_final,
            'activo' => $lote_produccion->activo,
            'fecha_registro' => $lote_produccion->fecha_registro,
          


        ];
    }
}