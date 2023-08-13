<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class GruposTrabajo extends Model
{
    use HasFactory;
    protected $fillable = ['nombre', 'cantidad_integrantes','tipo_grupo_id','muestras'];

    public function TipoGrupos(){
        return $this->belongsTo(TipoGrupo::class,'tipo_grupo_id','id');
    }

   public function personales(){
        return $this->hasMany(Personal::class,'id_grupo_trabajo');
   }

   public function LotesProduccion(): BelongsToMany
   {
       return $this->belongsToMany(LoteProduccion::class, 'asignacion_lotes')
                   ->withPivot('cantidad_asignada','porcentaje_avance','id_procesos')
                   ->withTimestamps();
   }

}
