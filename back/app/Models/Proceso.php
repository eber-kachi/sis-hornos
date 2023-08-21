<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proceso extends Model
{
    use HasFactory;

    use HasFactory;
    protected $fillable=['marcado_planchas','cortado_planchas','plegado_planchas',
        'soldadura','prueba_conductos','armado_cuerpo','pintado','armado_accesorios'];
    public function asignacion_lotes(){
        return $this->hasMany(AsignacionLote::class);
    }

    public function  EnEspera($query) {
        return $query->where("estado", "En espera");
    }
}
