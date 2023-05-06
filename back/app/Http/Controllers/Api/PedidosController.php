<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Exception;
use App\Http\Controllers\Api\Controller;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class PedidosController extends Controller
{
    
    public function index()
    {
        $pedidos = Pedido::paginate(25);

        $data = $pedidos->transform(function ($pedidos) {
            return $this->transform($pedidos);
        });

        return $this->successResponse(
            'Productoss were successfully retrieved.',
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

            $pedidos = Pedido::create($data);

            return $this->successResponse(
                'Grupos Trabajos was successfully added.',
                $this->transform($pedidos)
            );
        } catch (Exception $exception) {
            return $this->errorResponse('Unexpected error occurred while trying to process your request.');
        }
    }

    /**
     * Display the specified Productos.
     *
     * @param int $id
     *
     * @return Illuminate\Http\Response
     */
    public function show($id)
    {
        $pedidos = Pedido::findOrFail($id);

        return $this->successResponse(
            'Grupos Trabajos was successfully retrieved.',
            $this->transform($pedidos)
        );
    }

    /**
     * Update the specified Productos in the storage.
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

            $pedidos = Pedido::findOrFail($id);
            $pedidos->update($data);

            return $this->successResponse(
                'Grupos Trabajos was successfully updated.',
                $this->transform($pedidos)
            );
        } catch (Exception $exception) {
            return $this->errorResponse('Unexpected error occurred while trying to process your request.');
        }
    }

    /**
     * Remove the specified Productos from the storage.
     *
     * @param int $id
     *
     * @return Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $pedidos = Pedido::findOrFail($id);
            $pedidos->delete();

            return $this->successResponse(
                'Materiles was successfully deleted.',
                $this->transform($pedidos)
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
            "caracteristicas" => "nullable|string",
            "precio_unitario" => "nullable|numeric|min:0",
            "costo" => "nullable|numeric|min:0",
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
            "caracteristicas" => "nullable|string",
            "precio_unitario" => "nullable|numeric|min:0",
            "costo" => "nullable|numeric|min:0",
            'enabled' => 'boolean',

        ];


        $data = $request->validate($rules);


        $data['enabled'] = $request->has('enabled');


        return $data;
    }

    /**
     * Transform the giving Productos to public friendly array
     *
     * @param App\Models\Productos $pedidos
     *
     * @return array
     */
    protected function transform(Pedido $pedidos)
    {

        return [
            'id' => $pedidos->id,
            'nombre' => $pedidos->nombre,
            'caracteristicas' => $pedidos->caracteristicas,
            'precio_unitario' => $pedidos->precio_unitario,
            'costo' => $pedidos->costo,


        ];
    }
}
