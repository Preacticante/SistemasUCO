<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Usuario extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $table = 'usuario';

    public $timestamps = false; 

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

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    public function getEmailForPasswordReset()
    {
        return $this->correo;
    }

    public function routeNotificationForMail($notification)
    {
        return $this->correo;
    }

    protected static function booted()
    {
        static::creating(function ($usuario) {
            $anioActual = Carbon::now()->year;

            $ultimoUsuario = static::where('id_acceso', 'LIKE', "UCO-{$anioActual}-%")
                                    ->orderBy('id_acceso', 'desc')
                                    ->first();

            $nuevoNumero = $ultimoUsuario ? ((int) substr($ultimoUsuario->id_acceso, -3)) + 1 : 1;
            
            $usuario->id_acceso = "UCO-{$anioActual}-" . str_pad($nuevoNumero, 3, '0', STR_PAD_LEFT);
        });
    }
}