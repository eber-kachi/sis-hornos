<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Pedido extends Model
{
    protected $fillable = ['fecha_pedido','total_precio','lote_produccion_id',
                            'cliente_id'];
    use HasFactory;


    public function productos(): BelongsToMany
    {
        return $this->belongsToMany(Producto::class, 'concepto_pedidos')
                    ->withPivot('cantidad', 'precio')
                    ->withTimestamps();
    }

    public function clientes(){
        return $this->belongsTo(Cliente::class,'cliente_id','id');
    }

    public function lotesProducion(){
        return $this->belongsTo(LoteProduccion::class,'lote_produccion_id','id');
    }


}
