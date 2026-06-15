<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Puesto extends Model
{
    use SoftDeletes;

    protected $table = 'puestos';
    public $timestamps = false;
    
    protected $fillable = ['nombre'];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];
}