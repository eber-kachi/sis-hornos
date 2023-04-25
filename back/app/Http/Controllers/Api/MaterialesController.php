<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Exception;
use App\Http\Controllers\Api\Controller;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MaterialesController extends Controller
{
    public function index()
    {
        $materiales = Material::paginate(25);

        $data = $materiales->transform(function ($materiales) {
            return $this->transform($materiales);
        });

        return $this->successResponse(
            'Materialss were successfully retrieved.',
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

            $materiales = Material::create($data);

            return $this->successResponse(
                'Grupos Trabajos was successfully added.',
                $this->transform($materiales)
            );
        } catch (Exception $exception) {
            return $this->errorResponse('Unexpected error occurred while trying to process your request.');
        }
    }

    /**
     * Display the specified Materials.
     *
     * @param int $id
     *
     * @return Illuminate\Http\Response
     */
    public function show($id)
    {
        $materiales = Material::findOrFail($id);

        return $this->successResponse(
            'Grupos Trabajos was successfully retrieved.',
            $this->transform($materiales)
        );
    }

    /**
     * Update the specified Materials in the storage.
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

            $materiales = Material::findOrFail($id);
            $materiales->update($data);

            return $this->successResponse(
                'Grupos Trabajos was successfully updated.',
                $this->transform($materiales)
            );
        } catch (Exception $exception) {
            return $this->errorResponse('Unexpected error occurred while trying to process your request.');
        }
    }

    /**
     * Remove the specified Materials from the storage.
     *
     * @param int $id
     *
     * @return Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $materiales = Material::findOrFail($id);
            $materiales->delete();

            return $this->successResponse(
                'Materiles was successfully deleted.',
                $this->transform($materiales)
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
            "kg" => "nullable|float|min:0",
            "largo" => "nullable|float|min:0",
            "ancho" => "nullable|float|min:0",
            "cm" => "nullable|float|min:0",
            "cm2" => "nullable|float|min:0",
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
            "nombre" => "required|string",
            "kg" => "nullable|float|min:0",
            "largo" => "nullable|float|min:0",
            "ancho" => "nullable|float|min:0",
            "cm" => "nullable|float|min:0",
            "cm2" => "nullable|float|min:0",
            'enabled' => 'boolean',

        ];


        $data = $request->validate($rules);


        $data['enabled'] = $request->has('enabled');


        return $data;
    }

    /**
     * Transform the giving Materials to public friendly array
     *
     * @param App\Models\Materials $materiales
     *
     * @return array
     */
    protected function transform(Material $materiales)
    {

        return [
            'id' => $materiales->id,
            'nombre' => $materiales->nombre,
            'kg' => $materiales->kg,
            'largo' => $materiales->largo,
            'ancho' => $materiales->ancho,
            'cm' => $materiales->cm,
            'cm2' => $materiales->cm2,


        ];
    }
}