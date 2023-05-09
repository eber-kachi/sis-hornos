<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Exception;
use App\Http\Controllers\Api\Controller;
use App\Models\Medida;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MedidasController extends Controller
{
    
    public function index()
    {
        $medida = Medida::orderBy('id', 'desc')->get();

        $data = $medida->transform(function ($medida) {
            return $this->transform($medida);
        });

        return $this->successResponse(
            'Medidas were successfully retrieved.',
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

            $medida = Medida::create($data);

            return $this->successResponse(
                'Medida was successfully added.',
                $this->transform($medida)
            );
        } catch (Exception $exception) {
            return $this->errorResponse('Unexpected error occurred while trying to process your request.');
        }
    }

    /**
     * Display the specified Medidas.
     *
     * @param int $id
     *
     * @return Illuminate\Http\Response
     */
    public function show($id)
    {
        $medida = Medida::findOrFail($id);

        return $this->successResponse(
            'Medidas was successfully retrieved.',
            $this->transform($medida)
        );
    }

    /**
     * Update the specified Medidas in the storage.
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

            $medida = Medida::findOrFail($id);
            $medida->update($data);

            return $this->successResponse(
                'Medidas was successfully updated.',
                $this->transform($medida)
            );
        } catch (Exception $exception) {
            return $this->errorResponse('Unexpected error occurred while trying to process your request.');
        }
    }

    /**
     * Remove the specified Medidas from the storage.
     *
     * @param int $id
     *
     * @return Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $medida = Medida::findOrFail($id);
            $medida->delete();

            return $this->successResponse(
                'Medida was successfully deleted.',
                $this->transform($medida)
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

            "nombre" => "required|string",
            'enabled' => 'boolean',        ];

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

            "nombre" => "required|string",
            'enabled' => 'boolean',

        ];


        $data = $request->validate($rules);


        $data['enabled'] = $request->has('enabled');


        return $data;
    }

    /**
     * Transform the giving Medidas to public friendly array
     *
     * @param App\Models\Medidas $medida
     *
     * @return array
     */
    protected function transform(Medida $medida)
    {

        return [
            'id' => $medida->id,
            'nombre' => $medida->nombre,
        ];
    }
}
