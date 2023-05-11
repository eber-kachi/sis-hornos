<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Exception;
use App\Http\Controllers\Api\Controller;
use App\Models\AsignacionLote;
use App\Models\LoteProduccion;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoteProduccionController extends Controller
{

    private Modelomatematico $modelomatematico;
    public function __construct()
    {
        $this->modelomatematico = new Modelomatematico();
    }

    public function index()
    {
        $lote_produccion = LoteProduccion::orderBy('id', 'desc')->get();

        $data = $lote_produccion->transform(function ($lote_produccion) {
            return $this->transform($lote_produccion);
        });

        return $this->successResponse(
            'Lote Produccion were successfully retrieved.',
            $data
        );

    }

    public function store(Request $request)
    {

        try {

            // Crear un array con los ids y las cantidades de los pedidos
            $pedidos_ids = array();
            $pedidos_cantidades = array();
            foreach ($request->pedidos as $requestPedido) {
            $pedidos_ids[] = $requestPedido['id'];
            $pedidos_cantidades[] = $requestPedido['cantidad'];
            }

            // Sumar las cantidades usando array_sum()
            $suma = array_sum($pedidos_cantidades);

            // Asignar la suma a la variable cantidad de lote
            $lote = new LoteProduccion();
            $lote->cantidad = $suma;
            $lote->fecha_registro = today();
            $lote->activo="activo";
            $lote->save();

            // Actualizar el lote de producción en los pedidos con el método update()
            Pedido::whereIn('id', $pedidos_ids)->update(['lote_produccion_id' => $lote->id]);
            
                // Usando el método attach()
            $asignacion = $this->modelomatematico->cantidadProductosAsignados($lote);
            foreach ($asignacion as $loteAsignacion) {
                $lote->GruposTrabajo()->attach($loteAsignacion->grupos_trabajo_id,
                 ['cantidad_asignada' => $loteAsignacion->cantidad_asignada]);
            }


            return $this->successResponse(
                'Lote  Produccion was successfully added.',
                $this->transform($lote)
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
        $lote_produccion = LoteProduccion::findOrFail($id);

        return $this->successResponse(
            'Lote  Produccion was successfully retrieved.',
            $this->transform($lote_produccion)
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

        public function update(Request $request, $id)
        {
        try {
            // Obtener el lote de producción por el id
            $lote = LoteProduccion::findOrFail($id);

            // Crear un array con los ids y las cantidades de los pedidos
            $pedidos_ids = array();
            $pedidos_cantidades = array();
            foreach ($request->pedidos as $requestPedido) {
            $pedidos_ids[] = $requestPedido['id'];
            $pedidos_cantidades[] = $requestPedido['cantidad'];
            }

            // Sumar las cantidades usando array_sum()
            $suma = array_sum($pedidos_cantidades);

            // Asignar la suma a la variable cantidad de lote
            $lote->fill([
            'cantidad' => $suma,
            'fecha_registro' => today(),
            'activo' => "activo"
            ]);
            $lote->save();

            // Actualizar el lote de producción en los pedidos con el método sync()
            $asignacion = $this->modelomatematico->cantidadProductosAsignados($lote);
            $grupos_ids = array();
            $atributos = array();
            foreach ($asignacion as $loteAsignacion) {
            $grupos_ids[] = $loteAsignacion->grupos_trabajo_id;
            $atributos[$loteAsignacion->grupos_trabajo_id] = ['cantidad_asignada' => $loteAsignacion->cantidad_asignada];
            }
            $lote->gruposTrabajo()->sync($grupos_ids, $atributos);

            return $this->successResponse(
            'Lote  Produccion was successfully updated.',
            $this->transform($lote)
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
          // Obtener el lote de producción por el id
          $lote = LoteProduccion::findOrFail($id);
      
          // Quitar la relación con los pedidos usando detach()
          $lote->pedidos()->detach();
      
          // Eliminar el lote de producción
          $lote->delete();
      
          return $this->successResponse(
            'Lote  Produccion was successfully updated.',
            $this->transform($lote)
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
            "cantidad" => "nullable",
            "fecha_inicio" => "nullable",
            "fecha_final" => "nullable",
            "activo" => "string",

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
            "cantidad" => "nullable",
            "fecha_inicio" => "nullable",
            "fecha_final" => "nullable",
            "activo" => "string",


        ];


        $data = $request->validate($rules);

        return $data;
    }

    /**
     * Transform the giving Productos to public friendly array
     *
     * @param App\Models\Productos $lote_produccion
     *
     * @return array
     */
  
    protected function transform(LoteProduccion $lote_produccion)
    {
        // Cargar las relaciones del lote de producción
        $lote_produccion->load('grupos_trabajos', 'pedidos', 'asignacion_lotes');

        // Unir el array de grupos con el de asignación
        $grupos_asignacion = $lote_produccion->GruposTrabajo->merge($lote_produccion->asignacionLotes);

        // Agrupar los elementos por el idgrupo
        $grupos_asignacion = $grupos_asignacion->groupBy('grupos_trabajo_id');

        return [
            'id' => $lote_produccion->id,
            'cantidad' => $lote_produccion->cantidad,
            'fecha_inicio' => $lote_produccion->fecha_inicio,
            'fecha_final' => $lote_produccion->fecha_final,
            'activo' => $lote_produccion->activo,
            'fecha_registro' => $lote_produccion->fecha_registro,
            'tiempo_dias' => $this->modelomatematico->tiempoProduccionLote($lote_produccion['cantidad']),
            // Transformar los grupos y las asignaciones
            'grupos_asignacion' => $grupos_asignacion->map(function ($grupo) {
            return [
                'id' => $grupo[0]->id,
                'nombre' => $grupo[0]->nombre,
                // Obtener la cantidad asignada desde el pivot o desde la asignación
                'cantidad_asignada' => isset($grupo[0]->pivot) ? $grupo[0]->pivot->cantidad_asignada : $grupo[1]->cantidad_asignada
            ];
            }),
            // Transformar los pedidos
            'pedidos' => $lote_produccion->Pedidos->map(function ($pedido) {
            return [
                'id' => $pedido->id,
                'cliente_id' => $pedido->cliente_id,
                'producto_id' => $pedido->producto_id,
                'cantidad' => $pedido->cantidad,
                'fecha_entrega' => $pedido->fecha_entrega
            ];
            })
        ];
}
}
