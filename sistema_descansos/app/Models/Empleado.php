<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $table = 'empleados';
    public $timestamps = false;
    
    protected $fillable = ['nombre_completo', 'fecha_ingreso', 'puesto_id'];
}