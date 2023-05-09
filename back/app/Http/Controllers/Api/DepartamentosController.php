<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Exception;
use App\Http\Controllers\Api\Controller;
use App\Models\Departamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartamentosController extends Controller
{



    public function index()
    {
        $departamento = Departamento::orderBy('id', 'desc')->get();

        $data = $departamento->transform(function ($departamento) {
            return $this->transform($departamento);
        });

        return $this->successResponse(
            'Departamentos were successfully retrieved.',
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

            $departamento = Departamento::create($data);

            return $this->successResponse(
                'Departamento was successfully added.',
                $this->transform($departamento)
            );
        } catch (Exception $exception) {
            return $this->errorResponse('Unexpected error occurred while trying to process your request.');
        }
    }

    /**
     * Display the specified Departamentos.
     *
     * @param int $id
     *
     * @return Illuminate\Http\Response
     */
    public function show($id)
    {
        $departamento = Departamento::findOrFail($id);

        return $this->successResponse(
            'Grupos Trabajos was successfully retrieved.',
            $this->transform($departamento)
        );
    }

    /**
     * Update the specified Departamentos in the storage.
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

            $departamento = Departamento::findOrFail($id);
            $departamento->update($data);

            return $this->successResponse(
                'Grupos Trabajos was successfully updated.',
                $this->transform($departamento)
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
            $departamento = Departamento::findOrFail($id);
            $departamento->delete();

            return $this->successResponse(
                'Departamento was successfully deleted.',
                $this->transform($departamento)
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
     * Transform the giving Departamentos to public friendly array
     *
     * @param App\Models\Departamentos $departamento
     *
     * @return array
     */
    protected function transform(Departamento $departamento)
    {

        return [
            'id' => $departamento->id,
            'nombre' => $departamento->nombre,
        ];
    }

}
