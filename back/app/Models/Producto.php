<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Producto extends Model
{
    protected $fillable = ['nombre', 'caracteristicas','precio_unitario','costo'];
    use HasFactory;


    public function materials(): BelongsToMany
    {
        return $this->belongsToMany(Material::class, 'material_productos')
                    ->withPivot('cantidad', 'descripcion')
                    ->withTimestamps();
    }

    public function pedidos(): BelongsToMany
    {
        return $this->belongsToMany(Pedido::class, 'concepto_pedidos')
                    ->withPivot('cantidad', 'precio')
                    ->withTimestamps();
    }

    public function TipoGrupos(){
        return $this->hasMany(TipoGrupo::class);
    }


}
