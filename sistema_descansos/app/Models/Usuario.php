<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuario';

    public $timestamps = false; // 👈 IMPORTANTE (arregla el error)

    protected $primaryKey = 'correo';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nombre_completo',
        'correo',
        'contrasena'
    ];

    protected $hidden = [
        'contrasena',
        'remember_token',
    ];

    // Laravel usará esta contraseña para login
    public function getAuthPassword()
    {
        return $this->contrasena;
    }
}