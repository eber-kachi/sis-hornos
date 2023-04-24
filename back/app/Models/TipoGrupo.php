<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoGrupo extends Model
{
    use HasFactory;
    protected $fillable=['nombre','cantidadProduccionDiaria'];
    public function GruposTrabajos(){
        return $this->hasMany(GruposTrabajo::class);
    }
}
