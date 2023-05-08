<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medida extends Model
{

    protected $fillable = ['nombre'];
   
    use HasFactory;

    public function Materiales(){
        return $this->hasMany(Material::class);
    }

}
