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
        $pedidos = Pedido::orderBy('id', 'desc')->paginate(25);

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


            $pedido = new Pedido();
            $pedido->total_precio = $total_precio;
            $pedido->fecha_pedido = now();
            $pedido->clientes()->associate($request->cliente_id);
            $pedido->estado="activo";

             $pedido->save();

             $conceptos = []; // inicializar el array vacío
             foreach ($request->conceptos as $index => $concepto) {
                 if (isset($concepto['producto_id'])) {
                     $conceptos[$concepto['producto_id']] = [
                         'cantidad' => $concepto['cantidad'],
                         'precio' => $concepto['precio'] ?? null
                     ];
                 }
             }
               // Sincronizar los materiales con los datos adicionales
               $pedido->productos()->attach($conceptos);

// Sincronizar los productos con los datos adicionales $pedido->productos()->sync($conceptos);


            return $this->successResponse(
                'Pedidos was successfully added.',
                $this->transform($pedido)

            );
        } catch (Exception $exception) {
            return $this->errorResponse('Unexpected error occurred while trying to process your request.');
        }
    }


    public function listarPedidosActivosPorProducto($producto_id)
    {
        // Buscar los pedidos que tienen el producto_id y el estado activo en la tabla pivote
        $pedidos = Pedido::whereHas('productos', function ($query) use ($producto_id) {
            $query->where('producto_id', $producto_id);
        })->where('estado', 'activo')->get();

        // Devolver  con los pedidos
        return $pedidos;
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

            $total_precio = collect($request->conceptos)->map(function ($concepto) {
                return $concepto['cantidad'] * $concepto['precio'];
            })->sum();


            $pedido->total_precio = $total_precio;
            $pedido->fecha_pedido = now();
            $pedido->clientes()->associate($request->cliente_id);
            $pedido->save();

            $conceptos = []; // inicializar el array vacío
            foreach ($request->conceptos as $index => $concepto) {
                if (isset($concepto['producto_id'])) {
                    $conceptos[$concepto['producto_id']] = [
                        'cantidad' => $concepto['cantidad'],
                        'precio' => $concepto['precio'] ?? null
                    ];
                }
            }
               // Sincronizar los materiales con los datos adicionales
               $pedido->productos()->sync($conceptos);



            // Devolver una respuesta de éxito con el pedido y sus conceptos
            return $this->successResponse(
                'Pedido was successfully updated.',
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
            $pedido->productos()->detach();
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
                'total_precio' => $pedidos->total_precio,
                'fecha_pedido' => $pedidos->fecha_pedido,
                'cliente_id' => $pedidos->cliente_id,

                // Obtener el objeto cliente completo
                'cliente' => $pedidos->clientes,

               // Obtener el array de concepto pedidos con el nombre y la cantidad
                'productos' => $pedidos->productos->map(function ($producto) {
                    return [
                        'nombre' => $producto->nombre,
                        'cantidad' => $producto->pivot->cantidad
                ];
                })
            ];

    }
}
