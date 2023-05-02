<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'rols';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    // public $incrementing = false;


    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
                  'name',
                  'display_name',
                  'enabled'
              ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * Get the userRol for this model.
     *
     * @return App\Models\UserRol
     */
    public function userRol()
    {
        return $this->hasOne('App\Models\UserRol','rol_id','id');
    }

    public function users()
    {
        // return $this->hasMany('App\Models\User');
        return $this->hasMany('App\Models\User');
        // return $this->belongsToMany('App\Models\User', 'user_rols');
    }



}
