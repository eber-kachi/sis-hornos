<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\AsignacionLotesController;
use App\Http\Controllers\Controller;
use App\Models\AsignacionLote;
use App\Models\GruposTrabajo;
use App\Models\LoteProduccion;
use App\Models\TipoGrupo;


class ModeloMatematico {
    // Tiempo de producción de un producto k por cada grupo de trabajo G4
    public $TPGn;
    private $grupoTrabajo;
    private $tipoGrupo;
    private $asignacionLote;

    public function __construct() {
        // Obtener los datos de la base de datos usando Eloquent
        $this->grupoTrabajo = GruposTrabajo::orderBy('id')->get();
        $this->tipoGrupo = TipoGrupo::orderBy('id')->get();
        $this->asignacionLote = collect();

        
    }
   

    public function cantidadProductosAsignados(LoteProduccion $loteProduccion) {
        // Calcular el porcentaje y la cantidad asignada a cada grupo
        $cantidadTotal = $this->cantidadTotalProductoDia();
        foreach ($this->grupoTrabajo as $grupo) {
            foreach ($this->tipoGrupo as $tipo) {
                if ($tipo->id == $grupo->tipo_grupo_id) {
                    // Crear una nueva asignación de lote
                    $asignacion = new AsignacionLote();
                    $asignacion->grupos_trabajo_id = $grupo->id;
                    $asignacion->lote_produccion_id = $loteProduccion->id;
                    // Calcular el porcentaje del grupo
                    $porcentaje = $this->porcentajeGrupo($tipo->cantidad_produccion_diaria, $cantidadTotal);
                    // Calcular la cantidad asignada al grupo
                    $asignacion->cantidad_asignada = $this->cantidadAsignada($porcentaje, $loteProduccion->cantidad);
                    // Añadir la asignación a la colección
                    $this->asignacionLote->push($asignacion);
                }
            }
        }
        
        // Devolver la colección de asignaciones de lote
        return $this->asignacionLote;
    }



    private function cantidadAsignada($porcentaje, $cantidad) {
        // Redondear la cantidad asignada según el porcentaje
        return round(($porcentaje * $cantidad) / 100);
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
        // Calcular el tiempo de producción sin tiempo muerto
        $tiempoProduccion = $cantidad / $cantidadTotal;
        // Añadir el 10% de tiempo muerto
        $tiempoMuerto = $tiempoProduccion * 0.1;
        $tiempoProduccion += $tiempoMuerto;
        // Devolver el tiempo de producción
        return $tiempoProduccion;
    }

// Convertir la cantidad total de producto por día
    public function cantidadTotalProductoDia() {
        // Inicializar la cantidad total a cero
        $cantidadTotal = 0;
        // Recorrer cada grupo de trabajo
        foreach ($this->grupoTrabajo as $grupo) {
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

}
