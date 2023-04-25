<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personal extends Model
{
    use HasFactory;

    protected $fillable =['nombres' ,'apellidos','carnet_identidad','fecha_nacimiento',
    'direccion','fecha_registro','id_grupoTrabajo'
    ,'user_id'];

    public function GruposTrabajo(){
        return $this->belongsTo(GruposTrabajo::class,'id_grupoTrabajo','id');
    }
   // public function User(){
     //   return $this->hasOne(User::class,'user_id','id');
    //}




}
