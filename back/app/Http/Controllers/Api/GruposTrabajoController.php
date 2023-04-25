<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Api\Exception;
use App\Http\Controllers\Api\Controller;
use Illuminate\Http\Request;
use App\Models\GruposTrabajo;
use App\Http\Controllers\Api\Validator;

class GruposTrabajoController extends Controller
{
    public function index()
    {
        $gruposTrabajos = GruposTrabajo::paginate(25);

        $data = $gruposTrabajos->transform(function ($gruposTrabajos) {
            return $this->transform($gruposTrabajos);
        });

        return $this->successResponse(
            'gruposTrabajoss were successfully retrieved.',
            $data,
            [
                'links' => [
                    'first' => $gruposTrabajos->url(1),
                    'last' => $gruposTrabajos->url($gruposTrabajos->lastPage()),
                    'prev' => $gruposTrabajos->previousPageUrl(),
                    'next' => $gruposTrabajos->nextPageUrl(),
                ],
                'meta' =>
                [
                    'current_page' => $gruposTrabajos->currentPage(),
                    'from' => $gruposTrabajos->firstItem(),
                    'last_page' => $gruposTrabajos->lastPage(),
                    'path' => $gruposTrabajos->resolveCurrentPath(),
                    'per_page' => $gruposTrabajos->perPage(),
                    'to' => $gruposTrabajos->lastItem(),
                    'total' => $gruposTrabajos->total(),
                ],
            ]
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
            
            $gruposTrabajos = GruposTrabajo::create($data);

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

        return $this->successResponse(
		    'Grupos Trabajos was successfully retrieved.',
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
            $validator = $this->getValidator($request);

            if ($validator->fails()) {
                return $this->errorResponse($validator->errors()->all());
            }

            $data = $this->getData($request);
            
            $gruposTrabajos = GruposTrabajo::findOrFail($id);
            $gruposTrabajos->update($data);

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
            "nombre" => "required|string",
            "cantidad_integrantes" => "required|numeric|min:0",
            "TipoGrupo_id" => "required",
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
            "nombre" => 'required|string|min:1|max:255',
            "cantidad_integrantes" => "required|numeric|min:0",
            "TipoGrupo_id" => "required",
            'enabled' => 'boolean', 
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
            'TipoGrupo_id' => $gruposTrabajos->TipoGrupo_id,
            
        ];
    }


}