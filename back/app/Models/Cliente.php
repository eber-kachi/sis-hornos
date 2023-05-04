<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = ['nombres', 'apellidos',
    'carnet_identidad','fechaNacimiento','provincia',
    'celular','departamento_id'];

    use HasFactory;
    public function Departamento(){
        return $this->belongsTo(Departamento::class,'departamento_id','id');
    }

}
