<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $fillable = ['nombre'];
    use HasFactory;

    public function Clientes(){
        return $this->hasMany(Cliente::class);
    }
}
