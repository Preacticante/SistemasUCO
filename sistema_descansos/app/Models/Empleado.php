<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $table = 'empleados';
    public $timestamps = false;
    
    protected $fillable = ['nombre','apellido_paterno', 'apellido_materno', 'fecha_ingreso', 'puesto_id'];

    public function puesto() {
        return $this->belongsTo(Puesto::class, 'puesto_id');
    }
}