<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsignacionLote extends Model
{
    protected $fillable = ['lote_produccion_id',
        'grupos_trabajo_id','cantidad_asignada',
        'porcentaje_avance','id_procesos'];
    use HasFactory;

    public function Procesos(){
        return $this->belongsTo(Proceso::class,'id_procesos','id');
    }


}
