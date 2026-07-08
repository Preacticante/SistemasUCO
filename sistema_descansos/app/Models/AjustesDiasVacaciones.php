<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AjustesDiasVacaciones extends Model
{
    protected $table = 'ajustes_dias_vacaciones';
    
    public $timestamps = true;
    
    protected $fillable = [
        'empleado_id',
        'anio',
        'dias',
        'motivo',
    ];

    protected $casts = [
        'empleado_id' => 'integer',
        'anio' => 'integer',
        'dias' => 'integer',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }
}
