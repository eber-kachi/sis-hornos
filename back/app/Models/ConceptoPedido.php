<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConceptoPedido extends Model
{
    protected $fillable = ['pedido_id','producto_id','cantidad','precio'];
    
    use HasFactory;
}
