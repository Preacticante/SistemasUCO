<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegistroDescanso extends Model
{
    use SoftDeletes;

    protected $table = 'registros_descanso';
    public $timestamps = false;
    
    protected $fillable = ['empleado_id', 'anio_calendario', 'mes', 'dias_tomados'];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];
}