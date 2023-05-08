<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Material extends Model
{
    protected $fillable = ['nombre', 'medida_id','caracteristica'];
    use HasFactory;


    public function Medidas(){
        return $this->belongsTo(Medida::class,'medida_id','id');
    }

    public function productos(): BelongsToMany
    {
        return $this->belongsToMany(Producto::class, 'material_productos')
                    ->withPivot('cantidad', 'descripcion')
                    ->withTimestamps();
    }

}
