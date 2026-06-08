<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Empleado extends Model
{
    use SoftDeletes;

    protected $table = 'empleados';
    
    public $timestamps = false;

    // 1. OBLIGATORIO: Indica a Laravel que trate a deleted_at como una fecha/timestamp
    protected $dates = ['deleted_at'=> 'datetime']; 

    const DELETED_AT = 'deleted_at';

    protected $fillable = [
        'nombre', 
        'apellido_paterno', 
        'apellido_materno', 
        'fecha_ingreso', 
        'puesto_id',
        'usuario_id',
        'deleted_at'
    ];

    public function puesto() {
        return $this->belongsTo(Puesto::class, 'puesto_id');
    }
}