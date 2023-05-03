<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialProductos extends Model
{


    protected $fillable = ['cantidad','descripcion',
     'kg','largo_cm','ancho_cm',
     'cm2','producto_id','material_id','producto_id'];
 
    use HasFactory;
}
