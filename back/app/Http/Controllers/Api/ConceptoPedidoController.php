<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Exception;
use App\Http\Controllers\Api\Controller;
use App\Models\ConceptoPedido;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConceptoPedidoController extends Controller
{
    public function index()
    {
        $concepto_pedido = ConceptoPedido::orderBy('id', 'desc')->get();

        $data = $concepto_pedido->transform(function ($concepto_pedido) {
            return $this->transform($concepto_pedido);
        });

        return $this->successResponse(
            'ConceptoPedido were successfully retrieved.',
            $data
           // $concepto_pedido
        );

    }

    public function indexProductoMaterial()
{
    $data= Pedido::with('productos')->get();


    return $this->successResponse(
        'ConceptoPedido were successfully retrieved.',
        $data
       // $concepto_pedido
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

            $concepto_pedido = ConceptoPedido::create($data);

            return $this->successResponse(
                'Asignacion Lotes was successfully added.',
                $this->transform($concepto_pedido)
            );
        } catch (Exception $exception) {
            return $this->errorResponse('Unexpected error occurred while trying to process your request.');
        }
    }

    /**
     * Display the specified ConceptoPedidos.
     *
     * @param int $id
     *
     * @return Illuminate\Http\Response
     */
    public function show($id)
    {
        $concepto_pedido = ConceptoPedido::findOrFail($id);

        return $this->successResponse(
            'Asignacion Lotes was successfully retrieved.',
            $this->transform($concepto_pedido)
        );
    }

    /**
     * Update the specified ConceptoPedidos in the storage.
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

            $concepto_pedido = ConceptoPedido::findOrFail($id);
            $concepto_pedido->update($data);

            return $this->successResponse(
                'Asignacion Lotes was successfully updated.',
                $this->transform($concepto_pedido)
            );
        } catch (Exception $exception) {
            return $this->errorResponse('Unexpected error occurred while trying to process your request.');
        }
    }

    /**successResponse
     * Remove the specified ConceptoPedidos from the storage.
     *
     * @param int $id
     *
     * @return Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $concepto_pedido = ConceptoPedido::findOrFail($id);
            $concepto_pedido->delete();

            return $this->successResponse(
                'ConceptoPedido was successfully deleted.',
                $this->transform($concepto_pedido)
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
            "pedido_id" => "required",
            "producto_id" => "required",
            "cantidad" => "required|numeric|min:0",
            "precio" => "required|numeric|min:0",
            
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
            "pedido_id" => "required",
            "producto_id" => "required",
            "cantidad" => "required|numeric|min:0",
            "precio" => "required|numeric|min:0",
            
            'enabled' => 'boolean',
        ];


        $data = $request->validate($rules);


        $data['enabled'] = $request->has('enabled');


        return $data;
    }

    /**
     * Transform the giving ConceptoPedidos to public friendly array
     *
     * @param App\Models\ConceptoPedido $concepto_pedido
     *
     * @return array
     */
    protected function transform(ConceptoPedido $concepto_pedido)
    {

        return [
            'id' => $concepto_pedido->id,
            'pedido_id' => $concepto_pedido->pedido_id,
            'producto_id' => $concepto_pedido->producto_id,
            'cantidad' => $concepto_pedido->cantidad,
            'precio' => $concepto_pedido->precio,
    
        ];
    }
}
