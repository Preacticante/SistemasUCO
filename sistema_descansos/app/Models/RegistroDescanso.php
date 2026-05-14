<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistroDescanso extends Model
{
    protected $table = 'registros_descanso';
    public $timestamps = false;
    
    protected $fillable = ['empleado_id', 'anio_calendario', 'mes', 'dias_tomados'];
}