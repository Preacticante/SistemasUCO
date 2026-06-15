<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiaEspecial extends Model
{
    protected $table = 'dias_especiales';
    protected $fillable = [
        'tipo',
        'titulo',
        'fecha_inicio',
        'fecha_fin',
        'observaciones',
        'aplicado_a',
    ];

    protected $casts = [
        'fecha_inicio' => 'date:Y-m-d',
        'fecha_fin' => 'date:Y-m-d',
        'aplicado_a' => 'array',
    ];

    public function getColorAttribute(): string
    {
        return match ($this->tipo) {
            'descanso' => '#124416',
            'festivo' => '#AA7F31',
            'institucional' => '#6d28d9',
            default => '#124416',
        };
    }

    public function getTextColorAttribute(): string
    {
        return $this->tipo === 'festivo' ? '#000000' : '#ffffff';
    }
}
