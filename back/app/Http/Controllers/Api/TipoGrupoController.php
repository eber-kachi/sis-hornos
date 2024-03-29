<?php

namespace App\Http\Controllers\Api;
use Exception;
use App\Models\TipoGrupo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\Controller;

class TipoGrupoController extends Controller
{

    public function index()
    {
    
        $TipoGrupos = TipoGrupo::paginate(25);
        $data =$TipoGrupos->transform(function ($TipoGrupo) {
            return $this->transform($TipoGrupo);
        });

        return $this->successResponse(
            'Rols were successfully retrieved.',
            $data,
            [
                'links' => [
                    'first' => $TipoGrupos->url(1),
                    'last' => $TipoGrupos->url($TipoGrupos->lastPage()),
                    'prev' => $TipoGrupos->previousPageUrl(),
                    'next' => $TipoGrupos->nextPageUrl(),
                ],
                'meta' =>
                [
                    'current_page' => $TipoGrupos->currentPage(),
                    'from' => $TipoGrupos->firstItem(),
                    'last_page' => $TipoGrupos->lastPage(),
                    'path' => $TipoGrupos->resolveCurrentPath(),
                    'per_page' => $TipoGrupos->perPage(),
                    'to' => $TipoGrupos->lastItem(),
                    'total' => $TipoGrupos->total(),
                ],
            ]
        );


    }

    /**
     * Store a new tipo$tipoGrupo in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\Response
     */


    public function store(Request $request)
    {
        try {
            $validator = $this->getValidator($request);

            if ($validator->fails()) {
                return $this->errorResponse($validator->errors()->all());
            }

            $data = $this->getData($request);

            $tipoGrupo = TipoGrupo::create($data);

            return $this->successResponse(
			    'Tipo Grupo was successfully added.',
			    $this->transform($tipoGrupo)
			);
        } catch (Exception $exception) {
            return $this->errorResponse('Unexpected error occurred while trying to process your request.');
        }

    }

     /**
     * Display the specified tipo$tipoGrupo.
     *
     * @param int $id
     *
     * @return Illuminate\Http\Response
     */

    public function show($id)
    {
       $tipoGrupo = TipoGrupo::find($id);
       return $this->successResponse(
        'tipo$tipoGrupo was successfully retrieved.',
        $this->transform($tipoGrupo)
    );
    }
    public function update(Request $request, $id)
    {

        try {
            $validator = $this->getValidator($request);

            if ($validator->fails()) {
                return $this->errorResponse($validator->errors()->all());
            }

            $data = $this->getData($request);

            $tipoGrupo = TipoGrupo::findOrFail($id);
            $tipoGrupo->update($data);

            return $this->successResponse(
			    'TipoGrupo was successfully updated.',
			    $this->transform($tipoGrupo)
			);
        } catch (Exception $exception) {
            return $this->errorResponse('Unexpected error occurred while trying to process your request.');
        }

    }

    public function destroy($id)
    {
        try {
            $tipoGrupo = TipoGrupo::findOrFail($id);
            $tipoGrupo->delete();

            return $this->successResponse(
			    'tipoGrupo was successfully deleted.',
			    $this->transform($tipoGrupo)
			);
        } catch (Exception $exception) {
            return $this->errorResponse('Unexpected error occurred while trying to process your request.');
        }

    }


    protected function getValidator(Request $request)
    {
        $rules = [
            'nombre' => 'required|string|min:1|max:255',
            "cantidad_produccion_diaria" => "required|numeric|min:0",
            'enabled' => 'boolean',
        ];

        return Validator::make($request->all(), $rules);
    }

protected function getData(Request $request)
    {
        $rules = [
            'nombre' => 'required|string|min:1|max:255',
            "cantidad_produccion_diaria" => "required|numeric|min:0",
            'enabled' => 'boolean',
        ];


        $data = $request->validate($rules);


        $data['enabled'] = $request->has('enabled');


        return $data;
    }


    protected function transform(TipoGrupo $TipoGrupo)
    {
        return [
            'id' => $TipoGrupo->id,
            'nombre' => $TipoGrupo->nombre,
            'cantidad_produccion_diaria' => $TipoGrupo->cantidad_produccion_diaria,
        ];
    }

}
