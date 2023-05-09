<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Exception;
use App\Http\Controllers\Api\Controller;
use App\Models\ConceptoPedido;
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
            'Pedidos were successfully retrieved.',
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


            $total_precio = collect($request->conceptos)->map(function ($concepto) {
                return $concepto['cantidad'] * $concepto['precio'];
            })->sum();

            // Aquí puedes mostrar el valor de la variable $total_precio
           // echo $total_precio;

            //$data = $this->getData($request);
            $pedido = new Pedido();
            $pedido->total_precio = $total_precio;
            $pedido->fecha_pedido = now();
            $pedido->cliente_id = $request->cliente_id;
            $pedido->save();
            //echo $pedido;
    
         

            foreach ($request->conceptos as $request_concepto) {
                $concepto = new ConceptoPedido();
                $concepto->cantidad = $request_concepto['cantidad'];
                $concepto->producto_id = $request_concepto['producto_id'];
                $concepto->precio = $request_concepto['precio'];
                $concepto->pedido_id = $pedido->id;
                $concepto->save();

            }


            return $this->successResponse(
                'Pedidos was successfully added.',
                $this->transform($pedido)
            );
        } catch (Exception $exception) {
            return $this->errorResponse('Unexpected error occurred while trying to process your request.');
        }
    }

    /**
     * Display the specified Pedido.
     *
     * @param int $id
     *
     * @return Illuminate\Http\Response
     */
    public function show($id)
    {
        $pedidos = Pedido::findOrFail($id);

        return $this->successResponse(
            'Pedidos was successfully retrieved.',
            $this->transform($pedidos)
        );
    }

    /**
     * Update the specified Pedido in the storage.
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
                'Pedidos was successfully updated.',
                $this->transform($pedidos)
            );
        } catch (Exception $exception) {
            return $this->errorResponse('Unexpected error occurred while trying to process your request.');
        }
    }

    /**
     * Remove the specified Pedido from the storage.
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
            "total_precio" => "nullable|numeric|min:0",
            "lote_produccion_id" => "nullable",
            "cliente_id" => "required",
            "lote_produccion_id"=>"nullable",
            
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

            "total_precio" => "nullable|numeric|min:0",
            "lote_produccion_id" => "nullable",
            "cliente_id" => "required",
            "lote_produccion_id"=>"nullable",
            

        ];


        $data = $request->validate($rules);

        return $data;
    }

    /**
     * Transform the giving Pedido to public friendly array
     *
     * @param App\Models\Pedido $pedidos
     *
     * @return array
     */
    protected function transform(Pedido $pedidos)
    {

        return [
            'id' => $pedidos->id,
            'total_precio' =>  $pedidos->total_precio,
            'fecha_pedido' => $pedidos->fecha_pedido,
            'cliente_id' => $pedidos->cliente_id,
            


        ];
    }
}
