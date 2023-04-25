<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = ['nombre', 'kg','largo','ancho','cm','cm2'];
    use HasFactory;
}
