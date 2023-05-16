<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Http\Controllers\Api\Exception;
use App\Models\Personal;
use App\Models\User;
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

    public function singrupo()
    {
        // Obtener los personales que no tienen grupo de trabajo asignado
        $personales = Personal::whereNull('id_grupo_trabajo')->get();

        // Devolver los personales
            $data = $personales->transform(function ($personal) {
                return $this->transform($personal);
            });

            return $this->successResponse(
                'Personalss were successfully retrieved.',
                $data
            );

    }


        // Crear una función que liste al personal con id_grupo_trabajo null y con rol jefe de Contratos
    public function personalSinGrupoJefe()
     {
        // Obtener el personal con id_grupo_trabajo null y con rol jefe de Contratos
        $personal = Personal::whereNull('id_grupo_trabajo')->whereHas('user', function ($query) {
            $query->where('name', 'jefe de Contratos');
        })->get();

        dd($personal);

        // Devolver el personal
        $data = $personal->transform(function ($personal) {
            return $this->transform($personal);
        });

        return $this->successResponse(
            'Personal sin grupo de trabajo y con rol jefe de Contratos were successfully retrieved.',
            $data
        );

        }

        // Crear una función que liste al personal con id_grupo_trabajo null y que no tienen el rol jefe de Contratos
        public function personalsingruponojefe()
        {
        // Obtener el personal con id_grupo_trabajo null y que no tienen el rol jefe de Contratos
        $personal = Personal::whereNull('id_grupo_trabajo')->whereDoesntHave('user', function ($query) {
            $query->where('name', 'jefe de Contratos');
        })->get();

        // Devolver el personal
        $data = $personal->transform(function ($personal) {
            return $this->transform($personal);
        });

        return $this->successResponse(
            'Personal sin grupo de trabajo y que no tienen el rol jefe de Contratos were successfully retrieved.',
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
            $user = new User([
                'email' => $request->username."@gmail.com",
                'name' => $request->nombres,
                'username' => $request->username,
                'enabled' => 1,
                'password' => bcrypt($request->password),
                'rol_id' => $request->rol_id,
              ]);

              $user->save();



            // $personal = Personal::create($data);


            $personal = new Personal();
            $personal->nombres= $request->nombres;
            $personal->apellidos= $request->apellidos;
            $personal->carnet_identidad= $request->carnet_identidad;
            $personal->fecha_nacimiento= $request->fecha_nacimiento;
            $personal->fecha_registro = null; // now()->toDateTimeString('Y-m-d'); //2023-04-26
            $personal->direccion= $request->direccion;
            $personal->id_grupo_trabajo= NULL;
            $personal->user_id= $user->id;
            $personal->save();

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
        $personal = Personal::with('user','user.rol')->findOrFail($id);

        return $this->successResponse(
            'Personal was successfully retrieved.',
            $personal
            // $this->transform($personal)
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
            $this->transform($personal);

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
            'fecha_nacimiento' => 'nullable',
            'direccion' => 'required|string|min:1|max:255',
            'fecha_registro' => 'nullable',
            'id_grupo_trabajo' => "nullable",
            'user_id' => "nullable",
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
            'carnet_identidad' => 'string|min:1|max:255',
            'fecha_nacimiento' => 'nullable',
            'direccion' => 'required|string|min:1|max:255',
            'fecha_registro' => 'nullable',
            'id_grupo_trabajo' => "nullable",
            'user_id' => "nullable",


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
            'rol_id'=>optional($personal->user)->rol_id,
            'rol_nombre'=>optional($personal->user)->rol->display_name,
            'grupo_trabajo_nombre'=> optional($personal->GruposTrabajo)->nombre




        ];
    }


}

