<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Exception;
use App\Http\Controllers\Api\Controller;
use App\Models\AsignacionLote;
use App\Models\LoteProduccion;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
// use App\Http\Controllers\Api\Modelomatematico;

class LoteProduccionController extends Controller
{

    private ModeloMatematico $modelomatematico;

    public function __construct()
    {
        $this->modelomatematico = new ModeloMatematico();
    }

    public function index()
    {
        $lote_produccion = LoteProduccion::orderBy('id', 'desc')->paginate(10);

        $data = $lote_produccion->transform(function ($lote_produccion) {
            return $this->transform($lote_produccion);
        });

        return $this->successResponse(
            'Lote Produccion were successfully retrieved.',

            $data,
            [
                'links' => [
                    'first' => $lote_produccion->url(1),
                    'last' => $lote_produccion->url($lote_produccion->lastPage()),
                    'prev' => $lote_produccion->previousPageUrl(),
                    'next' => $lote_produccion->nextPageUrl(),
                ],
                'meta' =>
                    [
                        'current_page' => $lote_produccion->currentPage(),
                        'from' => $lote_produccion->firstItem(),
                        'last_page' => $lote_produccion->lastPage(),
                        'path' => $lote_produccion->resolveCurrentPath(),
                        'per_page' => $lote_produccion->perPage(),
                        'to' => $lote_produccion->lastItem(),
                        'total' => $lote_produccion->total(),
                    ],
            ]
        );

    }

    public function store(Request $request)
    {

        try {
            DB::beginTransaction(); // Iniciar transacción
            $pedidos_ids = array();
            $pedidos_cantidades = array();
            foreach ($request->pedidos as $requestPedido) {

                // Obtener el id del pedido
                $pedidos_ids[] = $requestPedido['id'];
                // Obtener el pedido usando el método find()
                $pedido = Pedido::find($requestPedido['id']);

                // Obtener el primer producto relacionado con el pedido
                $producto = $pedido->productos()->first();

                // Obtener la cantidad del producto desde el atributo pivot
                $pedidos_cantidades[] = $pedido->cantidad;
            }
            // Sumar las cantidades usando array_sum()
            $suma = array_sum($pedidos_cantidades);
            //$ultimo_lote = LoteProduccion::latest()->first();
            // Si hay un último lote, usar su fecha final más un día como fecha de inicio
            // Obtener el último lote con el id_producto que recibes

            $ultimo_lote = LoteProduccion::join('pedidos', 'lotes_produccion.id', '=', 'pedidos.lote_produccion_id')
                ->where('pedidos.producto_id', $request->producto_id)
                ->where('lotes_produccion.created_at', function ($query) use ($request) {
                    $query->from('lotes_produccion')
                        ->join('pedidos', 'lotes_produccion.id', '=', 'pedidos.lote_produccion_id')
                        ->selectRaw('MAX(lotes_produccion.created_at)')
                        ->where('pedidos.producto_id', $request->producto_id);
                })->first();


            if ($ultimo_lote) {
                $fecha_inicio = Carbon::parse($ultimo_lote->fecha_final)->addDay();
                // Si la fecha de inicio es sábado, sumar dos días
                if ($fecha_inicio->isWeekend()) {
                    $fecha_inicio->addDays(2);
                } // Si la fecha de inicio es domingo, sumar un día
                else if ($fecha_inicio->dayOfWeek == Carbon::SUNDAY) {
                    $fecha_inicio->addDay();
                }
            } // Si no hay un último lote, usar la fecha de hoy como fecha de inicio
            else {
                $fecha_inicio = Carbon::today();
            }
            // Asignar la suma a la variable cantidad de lote
            $lote = new LoteProduccion();
            $lote->cantidad = $suma;
            // color
            $color = $this->generarColorAleatorio();
            $lote->fecha_registro = today();
            $lote->estado = "Activo";
            $lote->color = $color;
            $lote->fecha_inicio = $fecha_inicio;
            $lote->porcentaje_total = 0;
            // Obtener el tiempo en días desde el modelo matemático
            $tiempo_dias = round($this->modelomatematico->tiempoProduccionLote($lote['cantidad'], $request->producto_id));
            // Crear una copia de la fecha de inicio
            $fecha_final = $fecha_inicio->copy();
            // Sumar el tiempo en días hábiles a la fecha final
            $fecha_final->addWeekdays($tiempo_dias);
            // Asignar la fecha final al lote de producción
            $lote->fecha_final = $fecha_final;
            $lote->save();
            $lotee = LoteProduccion::with('gruposTrabajos')->find($lote->id);
            // Actualizar el lote de producción y el estado en los pedidos con el método update()
            Pedido::whereIn('id', $pedidos_ids)->update([
                'lote_produccion_id' => $lote->id,
                'estado' => 'Asignado'
            ]);
            $asignacion = $this->modelomatematico->cantidadProductosAsignados($lote, $request->producto_id);

            $lotee->GruposTrabajos()->attach($asignacion);


            DB::commit(); // Confirmar transacción

            return $this->successResponse(
                ' Lotes was successfully added.',
                $this->transform($lotee)
            );

        } catch (Exception $exception) {

            DB::rollBack(); // Deshacer transacción
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
            DB::beginTransaction(); // Iniciar transacción
            $pedidos_ids = array();
            $pedidos_cantidades = array();
            foreach ($request->pedidos as $requestPedido) {

                // Obtener el id del pedido
                $pedidos_ids[] = $requestPedido['id'];
                // Obtener el pedido usando el método find()
                $pedido = Pedido::find($requestPedido['id']);

                // Obtener el primer producto relacionado con el pedido
                $producto = $pedido->productos()->first();

                // Obtener la cantidad del producto desde el atributo pivot
                $pedidos_cantidades[] = $pedido->cantidad;


            }
            // Sumar las cantidades usando array_sum()
            $suma = array_sum($pedidos_cantidades);

            $ultimo_lote = LoteProduccion::where('id', '<', $id)->latest()->first();

            // Si hay un lote anterior, usar su fecha final más un día como fecha de inicio
            if ($ultimo_lote) {
                $fecha_inicio = Carbon::parse($ultimo_lote->fecha_final)->addDay();
                // Si la fecha de inicio es sábado, sumar dos días
                if ($fecha_inicio->isWeekend()) {
                    $fecha_inicio->addDays(2);
                } // Si la fecha de inicio es domingo, sumar un día
                else if ($fecha_inicio->dayOfWeek == Carbon::SUNDAY) {
                    $fecha_inicio->addDay();
                }
            } // Si no hay un lote anterior, usar la fecha de hoy como fecha de inicio
            else {
                $fecha_inicio = Carbon::today();
            }
            // echo $suma;
            // Asignar la suma a la variable cantidad de lote
            $lote = LoteProduccion::find($id); // Buscar el lote por su id
            // echo $lote->cantidad;
            $lote->cantidad = $suma; // Asignar los nuevos valores
            // color
            $color = $this->generarColorAleatorio();
            $lote->fecha_registro = today();
            $lote->estado = "activo";
            $lote->color = $color;
            $lote->fecha_inicio = $fecha_inicio;
            $lote->porcentaje_total = 0;

            // Obtener el tiempo en días desde el modelo matemático
            $tiempo_dias = round($this->modelomatematico->tiempoProduccionLote($lote['cantidad'], $request->producto_id));
            // Crear una copia de la fecha de inicio
            $fecha_final = $fecha_inicio->copy();
            // Sumar el tiempo en días hábiles a la fecha final
            $fecha_final->addWeekdays($tiempo_dias);
            // Asignar la fecha final al lote de producción
            $lote->fecha_final = $fecha_final;
            $lote->save();
            // Poner null en el campo lote_produccion_id de todos los pedidos del lote
            Pedido::where('lote_produccion_id', $lote->id)->update(['lote_produccion_id' => null]);

            // Actualizar el lote de producción en los pedidos con el método update()
            Pedido::whereIn('id', $pedidos_ids)->update([
                'lote_produccion_id' => $lote->id,
                'estado' => 'Asignado'
            ]);
            $this->modelomatematico->cantidadProductosAsignados($lote, $request->producto_id);
            //echo $asignacion;
            DB::commit(); // Confirmar transacción
            return $this->successResponse(
                'Lote Produccion was successfully added.',
                $this->transform($lote)
            );
        } catch (Exception $exception) {

            DB::rollBack(); // Deshacer transacción
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


            // Poner null en el campo lote_produccion_id de todos los pedidos del lote
            Pedido::where('lote_produccion_id', $lote->id)->update([
                'lote_produccion_id' => null,
                'estado' => 'Activo'
            ]);


            // Eliminar el lote de producción
            $lote->delete();


            return $this->successResponse(
                ' Lotes was successfully added.',
                $this->transform($lote)
            );



        } catch (Exception $exception) {

            DB::rollBack(); // Deshacer transacción
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
     *  // Obtener la fecha de hoy
     *
     *
     * // Obtener el último lote de la tabla
     *
     *
     * @param App\Models\Productos $lote_produccion
     *
     * @return array
     */
// Generar un color aleatorio en formato hexadecimal
    function generarColorAleatorio()
    {
        // Crear un array con los posibles valores hexadecimales
        $valores = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F');

        // Inicializar el color con el símbolo #
        $color = '#';

        // Añadir seis valores aleatorios al color
        for ($i = 0; $i < 6; $i++) {
            // Elegir un valor al azar del array
            $valor = $valores[rand(0, 15)];

            // Añadir el valor al color
            $color .= $valor;
        }
        $color = strval($color);
        // Devolver el color generado
        return $color;
    }

    protected function transform(LoteProduccion $lote_produccion)
    {
        // Obtener solo los pedidos que tienen el mismo lote_produccion_id que el lote
        $id_producto= null;
        $pedidos = Pedido::with('clientes')->whereHas('lotesProducion', function ($query) use ($lote_produccion)
        { $query->where('id', $lote_produccion->id); })->get();
        foreach ($pedidos as $pedido) {$id_producto= $pedido->producto_id;
        }

        return [
            'id' => $lote_produccion->id,
            'cantidad' => $lote_produccion->cantidad,
            'fecha_inicio' => $lote_produccion->fecha_inicio,
            'fecha_final' => $lote_produccion->fecha_final,
            'estado' => $lote_produccion->estado,
            'color'=> $lote_produccion->color,
          'porcentaje_total' =>$lote_produccion->porcentaje_total,
            'fecha_registro' => $lote_produccion->fecha_registro,
            // // Transformar los grupos de trabajo
            'producto_id'=> $id_producto,
            'grupos_trabajo' => $lote_produccion->GruposTrabajos,
            // Transformar los pedidos con eager loading
            'pedidos' => $pedidos, ]; }


}
