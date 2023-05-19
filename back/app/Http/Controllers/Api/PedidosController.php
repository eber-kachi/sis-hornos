<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Exception;
use App\Http\Controllers\Api\Controller;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
class PedidosController extends Controller
{

    public function index()
    {
        $pedidos = Pedido::orderBy('id', 'desc')->paginate(25);

        $data = $pedidos->transform(function ($pedidos) {
            return $this->transform($pedidos);
        });


        return $this->successResponse(
            'Pedidos were successfully retrieved.',
            $data,
            [
                'links' => [
                    'first' => $pedidos->url(1),
                    'last' => $pedidos->url($pedidos->lastPage()),
                    'prev' => $pedidos->previousPageUrl(),
                    'next' => $pedidos->nextPageUrl(),
                ],
                'meta' =>
                    [
                        'current_page' => $pedidos->currentPage(),
                        'from' => $pedidos->firstItem(),
                        'last_page' => $pedidos->lastPage(),
                        'path' => $pedidos->resolveCurrentPath(),
                        'per_page' => $pedidos->perPage(),
                        'to' => $pedidos->lastItem(),
                        'total' => $pedidos->total(),
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
            // Buscar el producto con ese id
            $producto = Producto::find($request->producto_id);
            // Calcular el precio total usando el método sum
            $precio = $producto->precio_unitario * $request->cantidad;

            $pedido = new Pedido();
            $pedido->total_precio = $precio;
            $pedido->cantidad = $request->cantidad;
            $pedido->producto_id = $request->producto_id;
            $pedido->fecha_pedido = now();
            $pedido->clientes()->associate($request->cliente_id);

            $pedido->estado="Activo";

             $pedido->save();


            return $this->successResponse(
                'Pedidos was successfully added.',
                $this->transform($pedido)
            );
        } catch (Exception $exception) {
            return $this->errorResponse('Unexpected error occurred while trying to process your request.');
        }
    }

    public function listarPedidosActivosPorProducto (Request $request)
    {
        // Buscar los pedidos que tienen el producto_id y el estado activo en la tabla pivote
        $pedidos = Pedido::where('estado', 'activo')->where('producto_id', $request->producto_id)->paginate(25);;

         // Devolver con los pedidos
         $data = $pedidos->transform(function ($pedidos) {
             return $this->transform($pedidos);
         });


         return $this->successResponse(
             'Pedidos were successfully retrieved.',
             $data,
             [
                 'links' => [
                     'first' => $pedidos->url(1),
                     'last' => $pedidos->url($pedidos->lastPage()),
                     'prev' => $pedidos->previousPageUrl(),
                     'next' => $pedidos->nextPageUrl(),
                 ],
                 'meta' =>
                     [
                         'current_page' => $pedidos->currentPage(),
                         'from' => $pedidos->firstItem(),
                         'last_page' => $pedidos->lastPage(),
                         'path' => $pedidos->resolveCurrentPath(),
                         'per_page' => $pedidos->perPage(),
                         'to' => $pedidos->lastItem(),
                         'total' => $pedidos->total(),
                     ],
             ]
         );
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
            // Buscar el pedido por su id
            $pedido = Pedido::find($id);

            // Comprobar si existe
            if (!$pedido) {
                return $this->errorResponse('Pedido not found.');
            }
            // Validar los datos del request
            $validator = $this->getValidator($request);
            if ($validator->fails()) {
                return $this->errorResponse($validator->errors()->all());
            }
                // Buscar el producto con ese id
                $producto = Producto::find($request->producto_id);
                // Calcular el precio total usando el método sum
                $precio = $producto->precio_unitario * $request->cantidad;
                $pedido->total_precio = $precio;
                $pedido->cantidad = $request->cantidad;
                $pedido->producto_id = $request->producto_id;
                $pedido->fecha_pedido = now();
                $pedido->clientes()->associate($request->cliente_id);
                $pedido->estado="Activo";
                $pedido->save();
                return $this->successResponse(
                    'Pedidos was successfully added.',
                    $this->transform($pedido)

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

            $pedido = Pedido::find($id);
            // eliminar los conceptos relacionados
            $pedido->delete(); // eliminar el pedido

            return $this->successResponse(
                'Pedidos was successfully deleted.',
                $this->transform($pedido)
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
                'cantidad' => $pedidos->cantidad,
                'producto_id' => $pedidos->producto_id,
                'estado' => $pedidos->estado,

                'total_precio' => $pedidos->total_precio,
                'fecha_pedido' => $pedidos->fecha_pedido,
                'cliente_id' => $pedidos->cliente_id,

                // Obtener el objeto cliente completo
                'cliente' => $pedidos->clientes,
                'producto' => $pedidos->productos,

            ];

    }
}
