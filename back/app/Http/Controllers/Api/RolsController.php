<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Models\Rol;
use App\Http\Controllers\Api\Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class RolsController extends Controller
{

    /**
     * Display a listing of the assets.
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
        $rols = Rol::paginate(25);

        $data = $rols->transform(function ($rol) {
            return $this->transform($rol);
        });

        return $this->successResponse(
            'Rols were successfully retrieved.',
            $data,
            [
                'links' => [
                    'first' => $rols->url(1),
                    'last' => $rols->url($rols->lastPage()),
                    'prev' => $rols->previousPageUrl(),
                    'next' => $rols->nextPageUrl(),
                ],
                'meta' =>
                [
                    'current_page' => $rols->currentPage(),
                    'from' => $rols->firstItem(),
                    'last_page' => $rols->lastPage(),
                    'path' => $rols->resolveCurrentPath(),
                    'per_page' => $rols->perPage(),
                    'to' => $rols->lastItem(),
                    'total' => $rols->total(),
                ],
            ]
        );
    }

    /**
     * Store a new rol in the storage.
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
            
            $rol = Rol::create($data);

            return $this->successResponse(
			    'Rol was successfully added.',
			    $this->transform($rol)
			);
        } catch (Exception $exception) {
            return $this->errorResponse('Unexpected error occurred while trying to process your request.');
        }
    }

    /**
     * Display the specified rol.
     *
     * @param int $id
     *
     * @return Illuminate\Http\Response
     */
    public function show($id)
    {
        $rol = Rol::findOrFail($id);

        return $this->successResponse(
		    'Rol was successfully retrieved.',
		    $this->transform($rol)
		);
    }

    /**
     * Update the specified rol in the storage.
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
            
            $rol = Rol::findOrFail($id);
            $rol->update($data);

            return $this->successResponse(
			    'Rol was successfully updated.',
			    $this->transform($rol)
			);
        } catch (Exception $exception) {
            return $this->errorResponse('Unexpected error occurred while trying to process your request.');
        }
    }

    /**
     * Remove the specified rol from the storage.
     *
     * @param int $id
     *
     * @return Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $rol = Rol::findOrFail($id);
            $rol->delete();

            return $this->successResponse(
			    'Rol was successfully deleted.',
			    $this->transform($rol)
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
            'name' => 'required|string|min:1|max:255',
            'display_name' => 'required|string|min:1|max:255',
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
                'name' => 'required|string|min:1|max:255',
            'display_name' => 'required|string|min:1|max:255',
            'enabled' => 'boolean', 
        ];

        
        $data = $request->validate($rules);


        $data['enabled'] = $request->has('enabled');


        return $data;
    }

    /**
     * Transform the giving rol to public friendly array
     *
     * @param App\Models\Rol $rol
     *
     * @return array
     */
    protected function transform(Rol $rol)
    {
        return [
            'id' => $rol->id,
            'name' => $rol->name,
            'display_name' => $rol->display_name,
            'enabled' => ($rol->enabled) ? 'Yes' : 'No',
        ];
    }


}
