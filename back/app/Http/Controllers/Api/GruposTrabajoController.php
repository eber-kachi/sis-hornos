<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Api\Exception;
use App\Http\Controllers\Api\Controller;
use Illuminate\Http\Request;
use App\Models\GruposTrabajo;
use App\Models\Personal;
use App\Models\TipoGrupo ;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;


class GruposTrabajoController extends Controller
{

    private ModeloMatematico $modelomatematico;
    public function __construct()
    {
        $this->modelomatematico = new ModeloMatematico();
    }

    public function index()
    {
        $gruposTrabajos = GruposTrabajo::with(["tipoGrupos","personales"])->get();

        return $this->successResponse(
            'grupos de Trabajos were successfully retrieved.',
            $gruposTrabajos
        );

    }




    public function store(Request $request)
    {
        try {
            DB::beginTransaction(); // Iniciar transacción
            $validator = $this->getValidator($request);

            if ($validator->fails()) {
                return $this->errorResponse($validator->errors()->all());
            }

            $tipoGrupo = TipoGrupo::create([
                "nombre"=> 'Grupo',
                "cantidad_produccion_diaria"=> 0,
                "productos_id"=> $request->productos_id,

            ]);
            $coleccion = new Collection($request->produccion_diarias);

            // Convertir la colección en una cadena JSON
            $json = $coleccion->toJson();

            // Mostrar el resultado
            //echo $json;

            $gruposTrabajos = GruposTrabajo::create([
                "nombre"=> $request->nombre,
                "tipo_grupo_id"=> $tipoGrupo->id,
                "cantidad_integrantes"=> 0,
                "muestras" => $json,
            ]);


            $count=0;
            $personal= Personal::findOrFail($request->jefe_id);
            $personal->id_grupo_trabajo=$gruposTrabajos->id;
            $personal->save();
            $count++;
            foreach ($request->ayudantes as $key => $value) {
                // abort(500,$value );
                $personal= Personal::findOrFail($value);
                $personal->id_grupo_trabajo=$gruposTrabajos->id;
                $personal->save();
                $count++;
            }
            $gruposTrabajos = GruposTrabajo::findOrFail($gruposTrabajos->id);



            // Convertir la colección en una cadena JSON
            $gruposTrabajos->update([
                "cantidad_integrantes" =>  $count

            ]);
            $nombre =optional($tipoGrupo->Productos)->nombre;

            $tipoGrupo->update([
                "nombre" => "Grupo" . "$count" . "$nombre",
                "cantidad_produccion_diaria" => $this->modelomatematico->cantidad_produccion_diaria($request->produccion_diarias
                ),

            ]);
            DB::commit(); // Confirmar transacción
            return $this->successResponse(
			    'Grupos Trabajos was successfully added.',
			    $this->transform($gruposTrabajos)
			);
        } catch (Exception $exception) {

            DB::rollBack(); // Deshacer transacción
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

        return $this->successResponse(
            'Grupos de Trabajos was successfully deleted.',
            $this->transform($gruposTrabajos)
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

            DB::beginTransaction(); // Iniciar transacción
            $gruposTrabajos = GruposTrabajo::findOrFail($id);
            // $gruposTrabajos->update($data);

            $personales= Personal::where('id_grupo_trabajo','=',$gruposTrabajos->id)->get();
            foreach ($personales as $per) {
                $personal= Personal::findOrFail($per->id);
                $personal->id_grupo_trabajo = null;
                $personal->save();
            }
            $tipoGrupo = TipoGrupo::findOrFail($gruposTrabajos->tipo_grupo_id);
            $count=0;

            $personal= Personal::findOrFail($request->jefe_id);
            $personal->id_grupo_trabajo=$gruposTrabajos->id;
            $personal->save();
            $count++;
            foreach ($request->ayudantes as $key => $value) {
                // abort(500,$value );
                $personal= Personal::findOrFail($value);
                $personal->id_grupo_trabajo=$gruposTrabajos->id;
                $personal->save();
                $count++;
            }
            $gruposTrabajos = GruposTrabajo::findOrFail($gruposTrabajos->id);
            $coleccion = new Collection($request->produccion_diarias);
            // Convertir la colección en una cadena JSON
            $json = $coleccion->toJson();

            // Mostrar el resultado
            //echo $json;
            $gruposTrabajos->update([
                "nombre"=> $request->nombre,
                "cantidad_integrantes" =>  $count,
                "muestras" => $json,
            ]);
            $nombre =optional($tipoGrupo->Productos)->nombre;

            $tipoGrupo->update([
                "nombre" => "Grupo" . "$count" . "$nombre",
                "cantidad_produccion_diaria" => $this->modelomatematico->cantidad_produccion_diaria($request->produccion_diarias),

            ]);
            DB::commit(); // Confirmar transacción
            return $this->successResponse(
                'Grupos Trabajos was successfully added.',
                $this->transform($gruposTrabajos)
            );
        } catch (Exception $exception) {

            DB::rollBack(); // Deshacer transacción
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
            DB::beginTransaction(); // Iniciar transacción
            $this->updatePersonal($id);
            $gruposTrabajos = GruposTrabajo::findOrFail($id);
            $gruposTrabajos->delete();
            DB::commit(); // Confirmar transacción

            return $this->successResponse(
			    'Grupos de Trabajos was successfully deleted.',
			    $this->transform($gruposTrabajos)
			);
        }catch (Exception $exception) {
            DB::rollBack(); // Deshacer transacción
            // Modificar el mensaje de la excepción
            $exception->withMessage('El grupo tiene asignaciones. No se puede eliminar.');
            // Devolver el mensaje modificado
            return $exception->getMessage();
            //return $this->errorResponse('Unexpected error occurred while trying to process your request.');
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
        ];


        $data = $request->validate($rules);
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
            'jefe'=>$this->jefe($gruposTrabajos->id),
            'ayudantes'=>$this->lista_ayudantes($gruposTrabajos->id,),
            'nombre' => $gruposTrabajos->nombre,
            'cantidad_integrantes' => $gruposTrabajos->cantidad_integrantes,
            'tipo_grupo_id' => $gruposTrabajos->tipo_grupo_id,
            'tipo_grupo_nombre'=> optional($gruposTrabajos->tipoGrupos)->nombre,
            'producto'=> optional($gruposTrabajos->tipoGrupos)->Productos,
            'produccion_diarias'=>$gruposTrabajos->muestras,



        ];
    }

    protected function jefe ($id_gruposTrabajos) {

        $jefe = Personal::join('users', 'personals.user_id', '=', 'users.id')
        ->where('users.rol_id', '=', 6)
        ->where('personals.id_grupo_trabajo', '=', $id_gruposTrabajos)
        ->firstOrFail();

        return $jefe ;
        }

    protected function lista_ayudantes ($id_gruposTrabajos) {

            $personales = Personal::join('users', 'personals.user_id', '=', 'users.id')
                ->where('users.rol_id', '<>', 6)
                ->where('personals.id_grupo_trabajo', '=', $id_gruposTrabajos)
                ->get();

            return $personales;
    }
   function updatePersonal($id_eliminado):void{

        Personal::where ('id_grupo_trabajo', '=', $id_eliminado)->update ( ['id_grupo_trabajo' => null]);
    }


}
