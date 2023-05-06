<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class LoteProduccion extends Model
{

    protected $fillable = ['cantidad','fecha_inicio',
                         'fecha_final','activo','fecha_registro'];
    use HasFactory;

    public function Pedidos(){
        return $this->hasMany(Pedido::class);
    }


    public function GruposTrabajo(): BelongsToMany
    {
        return $this->belongsToMany(GruposTrabajo::class, 'asignacion_lotes')
                    ->withPivot('cantidad_asignada')
                    ->withTimestamps();
    }

}
