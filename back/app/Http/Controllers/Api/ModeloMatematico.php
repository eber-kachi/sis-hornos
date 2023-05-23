<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\AsignacionLotesController;
use App\Models\AsignacionLote;
use App\Models\GruposTrabajo;
use App\Models\LoteProduccion;
use App\Models\Proceso;
use App\Models\TipoGrupo;


class ModeloMatematico {
    // Tiempo de producción de un producto k por cada grupo de trabajo G4
    public $TPGn;
    private $grupoTrabajo;
    private $tipoGrupo;
    private $asignacionLote;

    public function __construct() {
        // Obtener los datos de la base de datos usando Eloquent
        //$this->grupoTrabajo = GruposTrabajo::orderBy('id')->get();
        $this->grupoTrabajo =array();
        $this->tipoGrupo = TipoGrupo::orderBy('id')->get();
        $this->asignacionLote = collect();


    }


    public function cantidadProductosAsignados( LoteProduccion $loteProduccion) {
        // Calcular el porcentaje y la cantidad asignada a cada grupo
        $this->grupoTrabajo = $this->obtenerGruposTrabajoPorLote($loteProduccion);
        $cantidadTotal = $this->cantidadTotalProductoDia( $this->grupoTrabajo);
       // $this->grupoTrabajo =$this->handle($tipo->id);
        $asignaciones = AsignacionLote::where('lote_produccion_id', $loteProduccion->id)->get();
        foreach ($this->grupoTrabajo as $grupo) {
            foreach ($this->tipoGrupo as $tipo) {
                if ($tipo->id == $grupo->tipo_grupo_id) {
                    if ($asignaciones->isEmpty()) {

                        // crear la Lista de procesos
                        $proceso = new Proceso();
                        $proceso->marcado_planchas="En espera";
                        $proceso->cortado_planchas="En espera";
                        $proceso->plegado_planchas="En espera";
                        $proceso->soldadura="En espera";
                        $proceso->prueba_conductos="En espera";
                        $proceso->armado_cuerpo="En espera";
                        $proceso->pintado="En espera";
                        $proceso->armado_accesorios="En espera";
                        $proceso->save();
                        // Crear una nueva asignación de lote
                        $asignacion = new AsignacionLote();
                        $asignacion->grupos_trabajo_id = $grupo->id;
                        $asignacion->lote_produccion_id = $loteProduccion->id;
                        // Calcular el porcentaje del grupo
                        $porcentaje = $this->porcentajeGrupo($tipo->cantidad_produccion_diaria, $cantidadTotal);
                        // Calcular la cantidad asignada al grupo
                        $asignacion->cantidad_asignada = $this->cantidadAsignada($porcentaje, $loteProduccion->cantidad);
                        $asignacion->id_procesos = $proceso->id;
                        $asignacion->porcentaje_avance=0;
                        // Añadir la asignación a la colección
                        $this->asignacionLote->push($asignacion);

                    }else{
                        $asignacion = AsignacionLote::where ([ ['lote_produccion_id', '=', $loteProduccion->id], ['grupos_trabajo_id', '=', $grupo->id] ])->first();
                        $porcentaje = $this->porcentajeGrupo($tipo->cantidad_produccion_diaria, $cantidadTotal);
                        // Calcular la cantidad asignada al grupo
                        $asignacion->cantidad_asignada = $this->cantidadAsignada($porcentaje, $loteProduccion->cantidad);
                        $asignacion->porcentaje_avance=0;
                        $asignacion->save();
                        // Añadir la asignación a la colección
                        $this->asignacionLote->push($asignacion);


                    }


                }
            }
        }

        // Devolver la colección de asignaciones de lote

        return $this->asignacionLote;
    }
    private function cantidadAsignada($porcentaje, $cantidad) {
        // Redondear la cantidad asignada según el porcentaje y el modo

        return round(($porcentaje * $cantidad) / 100, 0, PHP_ROUND_HALF_UP);

    }

    // Convertir el porcentaje de un grupo
    private function porcentajeGrupo($cantidad_produccion_diaria, $cantidadTotal) {
        // Calcular el porcentaje
        $porcentaje = ($cantidad_produccion_diaria * 100) / $cantidadTotal;
        return $porcentaje;
    }

// Convertir el tiempo de producción de un lote en días
    public function tiempoProduccionLote($cantidad) {
        // Obtener la cantidad total de producto por día
        $cantidadTotal = $this->cantidadTotalProductoDia();
        // Calcular el tiempo de producción sin tiempo muerto si es 0
        $tiempoProduccion = $cantidad / $cantidadTotal;
        // Añadir el 10% de tiempo muerto
        $tiempoMuerto = $tiempoProduccion * 0.1;
        $tiempoProduccion += $tiempoMuerto;
        // Devolver el tiempo de producción
        return $tiempoProduccion;
    }
// Convertir la cantidad total de producto por día
    public function cantidadTotalProductoDia(GruposTrabajo $grupoTrabajo ) {
        // Inicializar la cantidad total a cero
        $cantidadTotal = 0;
        // Recorrer cada grupo de trabajo
        foreach ($grupoTrabajo as $grupo) {
            // Recorrer cada tipo de grupo
            foreach ($this->tipoGrupo as $tipo) {
                // Si el tipo coincide con el grupo, sumar la cantidad de producción diaria
                if ($tipo->id == $grupo->tipo_grupo_id) {
                    $cantidadTotal += $tipo->cantidad_produccion_diaria;
                }
            }
        }
        // Devolver la cantidad total
        return $cantidadTotal;
    }
    public function cantidad_produccion_diaria(mixed $producion_diaria)
    {
        //$OTPGn Tiempo de Produccion de un Producto por el grupo de trabajo de $count integrantes
        $cantidad=0;
        $suma=0;
        foreach ($producion_diaria as  $value) {

            $suma += $value ["cantidad"];
            $cantidad++;
        }
        $OTPGn = $cantidad/$suma;
        //$CPD cantidad_produccion_diaria Tiempo de Produccion de un Producto por el grupo de trabajo de $count integrantes
        $CPD = 1/$OTPGn;
        return $CPD;

    }

    private function Stand_Deviation(mixed $arr)
    {
            $num_of_elements = count ($arr);
            $variance = 0.0;
            // Calcular la media usando array_sum ()
            $average = array_sum ($arr)/$num_of_elements;
            foreach ($arr as $i)
            {
                // Sumar los cuadrados de las diferencias entre
                // cada elemento y la media.
                $variance += pow ( ($i - $average), 2);
            }
            return (float)sqrt ($variance/$num_of_elements);

    }
    // En tu función
    private function obtenerGruposTrabajoPorLote(LoteProduccion $lote)
    {
        // Cargar los pedidos del lote
        $lote->load('pedidos');

        // Crear un array vacío para guardar los grupos de trabajo
        $grupos_trabajo = [];

        // Iterar sobre los pedidos del lote
        foreach ($lote->pedidos as $pedido) {
            // Obtener el id del producto del pedido
            $producto_id = $pedido->producto_id;

            // Obtener los grupos de trabajo que tengan el mismo id producto
            $grupos_trabajo[$producto_id] = GruposTrabajo::whereHas('tipo', function ($query) use ($producto_id) {
                $query->where('producto_id', $producto_id);
            })->get();
        }

        // Devolver el array de grupos de trabajo
        return $grupos_trabajo;
    }

}
