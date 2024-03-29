<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Api\Exception;
use App\Http\Controllers\Api\Controller;
use Illuminate\Http\Request;
use App\Models\Cliente;
use Illuminate\Support\Facades\Validator;

class ClientesController extends Controller
{
    public function index(Request $request)
    {
        // Obtener el término de búsqueda del request
        $search= $request->q;
        // Si hay un término de búsqueda, filtrar los clientes que coincidan con él
        if ($search) {
            $clientes = Cliente::where('nombres', 'like', "%$search%")
                ->orWhere('apellidos', 'like', "%$search%")
                ->orWhere('carnet_identidad', 'like', "%$search%")
                ->orWhere('provincia', 'like', "%$search%")
                ->orWhereHas('Departamento', function ($query) use ($search) {
                    $query->where('nombre', 'like', "%$search%");
                })
                ->orderBy('id', 'desc')
                ->paginate(10);
        } else {
            // Si no hay un término de búsqueda, obtener todos los clientes
            $clientes = Cliente::orderBy('id', 'desc')->paginate(10);
        }

        $data = $clientes->transform(function ($clientes) {
            return $this->transform($clientes);
        });
        return $this->successResponse(
            'Clientes were successfully retrieved.',
            $data,
            [
                'links' => [
                    'first' => $clientes->url(1),
                    'last' => $clientes->url($clientes->lastPage()),
                    'prev' => $clientes->previousPageUrl(),
                    'next' => $clientes->nextPageUrl(),
                ],
                'meta' =>
                    [
                        'current_page' => $clientes->currentPage(),
                        'from' => $clientes->firstItem(),
                        'last_page' => $clientes->lastPage(),
                        'path' => $clientes->resolveCurrentPath(),
                        'per_page' => $clientes->perPage(),
                        'to' => $clientes->lastItem(),
                        'total' => $clientes->total(),
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

            $clientes = Cliente::create($data);

            return $this->successResponse(
                'Clientes  was successfully added.',
                $this->transform($clientes)

            );
        } catch (Exception $exception) {
            return $this->errorResponse('Unexpected error occurred while trying to process your request.');
        }
    }

    /**
     * Display the specified Clientes.
     *
     * @param int $id
     *
     * @return Illuminate\Http\Response
     */
    public function show($id)
    {
        $clientes = Cliente::findOrFail($id);
        return $this->successResponse(
            'Cliente  was successfully retrieved.',
            $this->transform($clientes)
        );
    }

    /**
     * Update the specified Clientes in the storage.
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

            $clientes = Cliente::findOrFail($id);
            $clientes->update($data);

            return $this->successResponse(
                'Clientes  was successfully updated.',
                $this->transform($clientes)
            );
        } catch (Exception $exception) {
            return $this->errorResponse('Unexpected error occurred while trying to process your request.');
        }
    }

    /**
     * Remove the specified Clientes from the storage.
     *
     * @param int $id
     *
     * @return Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $clientes = Cliente::findOrFail($id);
            $clientes->delete();

            return $this->successResponse(
                'Clientes was successfully deleted.',
                $this->transform($clientes)
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
            'nombres' =>'required|string|min:1|max:255',
            'apellidos' => 'string|min:1|max:255',
            'carnet_identidad' => 'required|string|min:1|max:255',
            'provincia' => 'required|string|min:1|max:255',
            'fecha_nacimiento' => 'required|date|before:18 years ago',
            'departamento_id' => 'required',
            'celular' =>'required|numeric|min:0',
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

            'nombres' =>'required|string|min:1|max:255',
            'apellidos' => 'string|min:1|max:255',
            'carnet_identidad' => 'required|string|min:1|max:255',
            'provincia' => 'required|string|min:1|max:255',
            'departamento_id' => 'required',
            'fecha_nacimiento' => 'required|date|before:18 years ago',
            'celular' =>'required|numeric|min:0',
        ];


        $data = $request->validate($rules);
        return $data;
    }

    /**
     * Transform the giving Clientes to public friendly array
     *
     * @param App\Models\Clientes $clientes
     *
     * @return array
     */
    protected function transform(Cliente $clientes)
    {
        return [
            'id' => $clientes->id,
            'nombres' => $clientes->nombres,
            'apellidos' => $clientes->apellidos,
            'carnet_identidad' => $clientes->carnet_identidad,
            'fecha_nacimiento' => $clientes->fecha_nacimiento,
            'provincia' => $clientes->provincia,
            'celular' => $clientes->celular,
            'departamento_id' => $clientes->departamento_id,
            'depatarmento_nombre'=> optional($clientes->departamento)->nombre

        ];
    }


}
