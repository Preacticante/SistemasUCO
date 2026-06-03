<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;

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
        'contrasena',
        'id_acceso', 
        'departamento', 
        'fecha_alta', 
        'ultimo_acceso'
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

    protected static function booted()
    {
        // Este evento se dispara automáticamente milisegundos antes de insertarse en la BD
        static::creating(function ($usuario) {
            $anioActual = Carbon::now()->year;

            $ultimoUsuario = static::where('id_acceso', 'LIKE', "UCO-{$anioActual}-%")
                                    ->orderBy('id_acceso', 'desc')
                                    ->first();

            $nuevoNumero = $ultimoUsuario ? ((int) substr($ultimoUsuario->id_acceso, -3)) + 1 : 1;
            
            // Asigna el ID automáticamente
            $usuario->id_acceso = "UCO-{$anioActual}-" . str_pad($nuevoNumero, 3, '0', STR_PAD_LEFT);
        });
    }
}