<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Api\Exception;
use App\Http\Controllers\Api\Controller;
use Illuminate\Http\Request;
use App\Models\GruposTrabajo;
use App\Models\Personal;
use App\Models\TipoGrupo ;
use Illuminate\Support\Facades\Validator;


class GruposTrabajoController extends Controller
{

    private Modelomatematico $modelomatematico;
    public function __construct()
    {
        $this->modelomatematico = new Modelomatematico();
    }

    public function index()
    {
        $gruposTrabajos = GruposTrabajo::with(["tipoGrupos","personales"])->get();

        // $data = $gruposTrabajos->transform(function ($gruposTrabajos) {
        //     return $this->transform($gruposTrabajos);
        // });
        // dd( $gruposTrabajos);

        return $this->successResponse(
            'gruposTrabajoss were successfully retrieved.',
            $gruposTrabajos
        );

    }




    public function store(Request $request)
    {
        try {
            $validator = $this->getValidator($request);

            if ($validator->fails()) {
                return $this->errorResponse($validator->errors()->all());
            }

            $tipoGrupo = TipoGrupo::create([
                "nombre"=> 'Grupo',
                "cantidad_produccion_diaria"=> 0,
                "productos_id"=> $request->productos_id,

            ]);


            // $data = $this->getData($request);

            // $gruposTrabajos = GruposTrabajo::create($data);
            $gruposTrabajos = GruposTrabajo::create([
                "nombre"=> $request->nombre,
                "tipo_grupo_id"=> $tipoGrupo->id,
                "cantidad_integrantes"=> 0,
            ]);

            $count=0;
            foreach ($request->personales as $key => $value) {
                // abort(500,$value );
                $personal= Personal::findOrFail($value);
                $personal->id_grupo_trabajo=$gruposTrabajos->id;
                $personal->save();
                $count++;
            }
            $gruposTrabajos = GruposTrabajo::findOrFail($gruposTrabajos->id);

            $gruposTrabajos->update([
                "cantidad_integrantes" =>  $count
            ]);

            $tipoGrupo->update([
                "nombre" => "Grupo" . "$count" . "optional($tipoGrupo->Productos)->nombre",
                "cantidad_produccion_diaria" => $this->modelomatematico->cantidad_produccion_diaria($request->producion_diaria),

            ]);

            return $this->successResponse(
			    'Grupos Trabajos was successfully added.',
			    $this->transform($gruposTrabajos)
			);
        } catch (Exception $exception) {
            return $this->errorResponse('Unexpected error occurred while trying to process your request.');
        }
    }

    /**
     * Display the specified gruposTrabajos.
     *
     * @param int $id
     *
     * @return Illuminate\Http\Response
     */
    public function show($id)
    {
        $gruposTrabajos = GruposTrabajo::findOrFail($id);

        $personales= Personal::where('id_grupo_trabajo','=',$gruposTrabajos->id)->get();

        return $this->successResponse(
		    'Grupos Trabajos was successfully retrieved.',
		    [
                "grupo_trabajo"=> $gruposTrabajos,
                "personales"=> $personales,
            ]
		);
    }

    /**
     * Update the specified gruposTrabajos in the storage.
     *
     * @param int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        try {
            // $validator = $this->getValidator($request);

            // if ($validator->fails()) {
            //     return $this->errorResponse($validator->errors()->all());
            // }

            // $data = $this->getData($request);

            $gruposTrabajos = GruposTrabajo::findOrFail($id);
            // $gruposTrabajos->update($data);

            $personales= Personal::where('id_grupo_trabajo','=',$gruposTrabajos->id)->get();
            foreach ($personales as $per) {
                $personal= Personal::findOrFail($per->id);
                $personal->id_grupo_trabajo = null;
                $personal->save();
            }

            $count=0;
            foreach ($request->personales as $key => $value) {

                $personal= Personal::findOrFail($value);
                $personal->id_grupo_trabajo = $gruposTrabajos->id;
                $personal->save();
                $count++;
            }

            $gruposTrabajos = GruposTrabajo::findOrFail($gruposTrabajos->id);

            $gruposTrabajos->update([
                "nombre"=> $request->nombre,
                "tipo_grupo_id"=> $request->tipo_grupo_id,
                "cantidad_integrantes" =>  $count
            ]);

            return $this->successResponse(
			    'Grupos Trabajos was successfully updated.',
			    $this->transform($gruposTrabajos)
			);
        } catch (Exception $exception) {
            return $this->errorResponse('Unexpected error occurred while trying to process your request.');
        }
    }

    /**
     * Remove the specified gruposTrabajos from the storage.
     *
     * @param int $id
     *
     * @return Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $this->updatePersonal($id);
            $gruposTrabajos = GruposTrabajo::findOrFail($id);
            $gruposTrabajos->delete();

            return $this->successResponse(
			    'Grupos de Trabajos was successfully deleted.',
			    $this->transform($gruposTrabajos)
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
            "nombre" => 'required|string|min:1|max:255',
            "cantidad_integrantes" => "required|numeric|min:0",
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
            "nombre" => 'required|string|min:1|max:255',
            "cantidad_integrantes" => "required|numeric|min:0",
        ];


        $data = $request->validate($rules);


        $data['enabled'] = $request->has('enabled');


        return $data;
    }

    /**
     * Transform the giving gruposTrabajos to public friendly array
     *
     * @param App\Models\gruposTrabajos $gruposTrabajos
     *
     * @return array
     */
    protected function transform(GruposTrabajo $gruposTrabajos)
    {

        return [
            'id' => $gruposTrabajos->id,
            'nombre' => $gruposTrabajos->nombre,
            'cantidad_integrantes' => $gruposTrabajos->cantidad_integrantes,
            'tipo_grupo_id' => $gruposTrabajos->tipo_grupo_id,
            'tipo_grupo_nombre'=> optional($gruposTrabajos->tipoGrupos)->nombre


        ];
    }


   function updatePersonal($id_eliminado):void{

        Personal::where ('id_grupo_trabajo', '=', $id_eliminado)->update ( ['id_grupo_trabajo' => null]);
    }


}
