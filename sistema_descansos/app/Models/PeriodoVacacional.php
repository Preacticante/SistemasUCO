<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodoVacacional extends Model
{
    protected $table = 'periodos_vacacionales';
    public $timestamps = false;
    
    protected $fillable = ['empleado_id', 'anio_calendario', 'fecha_inicio', 'fecha_fin', 'fecha_regreso', 'dias'];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
}
