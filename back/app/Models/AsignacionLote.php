<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsignacionLote extends Model
{
    protected $fillable = ['lote_produccion_id','grupos_trabajo_id','cantidad_asignada'];
    use HasFactory;

    
}
