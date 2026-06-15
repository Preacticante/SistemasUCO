<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeyVacacion extends Model
{
    use SoftDeletes;

    protected $table = 'ley_vacaciones';
    protected $primaryKey = 'anios_antiguedad';
    public $incrementing = false;
    public $timestamps = false;
    
    protected $fillable = ['anios_antiguedad', 'dias_derecho'];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];
}