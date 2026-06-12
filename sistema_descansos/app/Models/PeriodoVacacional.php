<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class PeriodoVacacional extends Model
{
    use SoftDeletes;
    protected $table = 'periodos_vacacionales';
    
    // Deshabilitamos timestamps ya que la tabla original no los requiere
    public $timestamps = false;
    
    protected $fillable = [
        'empleado_id', 
        'anio_calendario', 
        'fecha_inicio', 
        'fecha_fin', 
        'fecha_regreso', 
        'dias', 
        'observaciones'
    ];

    /**
     * Casts automáticos de tipos de datos.
     * Convierte las cadenas de texto de la BD directamente a objetos Carbon.
     */
    protected $casts = [
        'fecha_inicio' => 'date:Y-m-d',
        'fecha_fin'    => 'date:Y-m-d',
        'fecha_regreso' => 'date:Y-m-d',
        'dias'          => 'integer',
        'empleado_id'   => 'integer',
        'deleted_at'    => 'datetime',
    ];

    /**
     * Relación con el modelo Empleado.
     * Se añade withDefault para evitar errores de tipo "Attempt to read property on null" 
     * en la vista si un empleado llega a faltar en la base de datos.
     */
    public function empleado()
    {
        return $this->belongsTo(Empleado::class)->withDefault([
            'nombre' => 'Empleado',
            'apellido_paterno' => 'No',
            'apellido_materno' => 'Encontrado'
        ]);
    }

    /**
     * Atributo dinámico para determinar el estado actual del período.
     * Uso en controladores o Blade: $periodo->estado
     */
    public function getEstadoAttribute(): string
    {
        if (!$this->fecha_fin) {
            return 'Programado';
        }

        // Al usar $casts, $this->fecha_fin ya es una instancia de Carbon automáticamente
        return $this->fecha_fin->isPast() ? 'Tomado' : 'Programado';
    }

    /**
     * Atributo dinámico para verificar si el período ya concluyó (booleano).
     * Uso en controladores o Blade: $periodo->ya_tomado
     */
    public function getYaTomadoAttribute(): bool
    {
        if (!$this->fecha_fin) {
            return false;
        }

        return $this->fecha_fin->isPast();
    }
}