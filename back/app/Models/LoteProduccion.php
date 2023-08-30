<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class LoteProduccion extends Model
{

    protected $table = 'lotes_produccion';
    protected $fillable = ['cantidad','fecha_inicio',
                         'fecha_final','color','fecha_registro','estado','porcentaje_total'];
    use HasFactory;

    public function Pedidos(){
        return $this->hasMany(Pedido::class);
    }
    public function GruposTrabajos(): BelongsToMany
    {
        return $this->belongsToMany(GruposTrabajo::class, 'asignacion_lotes')
                    ->withPivot('cantidad_asignada','porcentaje_avance','id_procesos')
                    ->withTimestamps();
    }

    public function verificarEstado() {
        // Obtener todos los pedidos del lote
        $pedidos = $this->Pedidos()->get();
        // Contar cuántos pedidos están entregados
        $entregados = $pedidos->where('estado', 'Entregado')->count();
        // Si todos los pedidos están entregados, cambiar el estado del lote a Terminado
        if ($entregados == $pedidos->count())
        { $this->estado = 'Terminado'; $this->save(); }
    }
}
