<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User  extends Authenticatable
{

    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;


    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
                  'name',
                  'username',
                  'email',
                  'email_verified_at',
                  'password',
                  'enabled',
                  'remember_token'
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

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the userRol for this model.
     *
     * @return App\Models\UserRol
     */
    public function userRol()
    {
        return $this->hasOne('App\Models\UserRol','user_id','id');
    }

    public function rols()
    {
        // return $this->belongsTo('App\Models\Rol');
        return $this->belongsToMany('App\Models\Rol', 'user_rols', 'user_id','rol_id');
//        return $this->belongsToMany('App\Models\Rol', 'user_rols', 'rol_id','user_id');
    }

    public function personales(){
        return $this->hasMany(Personal::class,'user_id');
   }





}
