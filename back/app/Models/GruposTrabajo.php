<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GruposTrabajo extends Model
{
    use HasFactory;
    protected $fillable = ['nombre', 'cantidadIntegrantes'];

    public function TipoGrupos(){
        return $this->belongsTo(TipoGrupo::class);
    }
}
