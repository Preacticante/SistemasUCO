<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeyVacacion extends Model
{
    protected $table = 'ley_vacaciones';
    protected $primaryKey = 'anios_antiguedad';
    public $incrementing = false;
    public $timestamps = false;
    
    protected $fillable = ['anios_antiguedad', 'dias_derecho'];
}