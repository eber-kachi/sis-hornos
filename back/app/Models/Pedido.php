<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Pedido extends Model
{
    protected $fillable = ['fecha_pedido','total_precio','lote_produccion_id','estado',
                            'cliente_id','cantidad','producto_id'];
    use HasFactory;

    public function productos(){
        return $this->belongsTo(Producto::class,'producto_id','id');
    }

    public function clientes(): BelongsTo{
        return $this->belongsTo(Cliente::class,'cliente_id','id');
    }

    public function lotesProducion(){
        return $this->belongsTo(LoteProduccion::class,'lote_produccion_id','id');
    }


}
