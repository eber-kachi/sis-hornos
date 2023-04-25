<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Http\Controllers\Api\Exception;
use App\Models\Personal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PersonalController extends Controller
{
    public function index()
    {
        $personal = Personal::with("GruposTrabajo")->get();

        $data = $personal->transform(function ($personal) {
            return $this->transform($personal);
        });

        return $this->successResponse(
            'Personalss were successfully retrieved.',
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

            $personal = Personal::create($data);

            return $this->successResponse(
                'Personal was successfully added.',
                $this->transform($personal)
            );
        } catch (Exception $exception) {
            return $this->errorResponse('Unexpected error occurred while trying to process your request.');
        }
    }

    /**
     * Display the specified Personals.
     *
     * @param int $id
     *
     * @return Illuminate\Http\Response
     */
    public function show($id)
    {
        $personal = Personal::findOrFail($id);

        return $this->successResponse(
            'Personal was successfully retrieved.',
            $this->transform($personal)
        );
    }

    /**
     * Update the specified Personals in the storage.
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

            $personal = Personal::findOrFail($id);
            $personal->update($data);

            return $this->successResponse(
                'Personal was successfully updated.',
                $this->transform($personal)
            );
        } catch (Exception $exception) {
            return $this->errorResponse('Unexpected error occurred while trying to process your request.');
        }
    }

    /**
     * Remove the specified Personals from the storage.
     *
     * @param int $id
     *
     * @return Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $personal = Personal::findOrFail($id);
            $personal->delete();

            return $this->successResponse(
                'Personal was successfully deleted.',
                $this->transform($personal)
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
            'nombres' => 'required|string|min:1|max:255',
            'apellidos' => 'required|string|min:1|max:255',
            'carnet_identidad' => 'string|min:1|max:255',
            'fecha_nacimiento' => 'date_format:j/n/Y g:i A',
            'direccion' => 'required|string|min:1|max:255',
            'fecha_registro' => 'required|date_format:j/n/Y g:i A',
            'id_grupo_trabajo' => "required",
            'user_id' => "required",
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

            'nombres' => 'required|string|min:1|max:255',
            'apellidos' => 'required|string|min:1|max:255',
            'carnet_identidad' => 'required|string|min:1|max:255',
            'fecha_nacimiento' => 'required|date_format:j/n/Y g:i A',
            'direccion' => 'required|string|min:1|max:255',
            'fecha_registro' => 'required|date_format:j/n/Y g:i A',
            'id_grupo_trabajo' => "nullable",
            'user_id' => "required",
        ];


        $data = $request->validate($rules);


        $data['enabled'] = $request->has('enabled');


        return $data;
    }

    /**
     * Transform the giving Personals to public friendly array
     *
     * @param App\Models\Personals $personal
     *
     * @return array
     */
    protected function transform(Personal $personal)
    {

        return [
            'id' => $personal->id,
            'nombres' => $personal->nombres,
            'apellidos' => $personal->apellidos,
            'carnet_identidad' => $personal->carnet_identidad,
            'fecha_nacimiento' => $personal->fecha_nacimiento,
            'direccion' => $personal->direccion,
            'fecha_registro' => $personal->fecha_registro,
            'id_grupo_trabajo' => $personal->id_grupo_trabajo,
            'user_id' => $personal->user_id,
            'grupo_trabajo_nombre'=> optional($personal->GruposTrabajo)->nombre




        ];
    }


}

