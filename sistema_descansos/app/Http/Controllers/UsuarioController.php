<?php


namespace App\Http\Controllers;
use App\Models\Usuario;
use Carbon\Carbon;

public function store(Request $request) 
{
    // 1. Obtener el año actual (ej: 2026)
    $anioActual = Carbon::now()->year; 

    // 2. Buscar el último usuario creado en este año que tenga un ID de acceso válido
    $ultimoUsuario = Usuario::where('id_acceso', 'LIKE', "UCO-{$anioActual}-%")
                            ->orderBy('id_acceso', 'desc')
                            ->first();

    if ($ultimoUsuario) {
        // Si ya hay usuarios este año, extraemos los últimos 3 dígitos (ej: de 'UCO-2026-015' toma '015')
        $ultimoNumero = (int) substr($ultimoUsuario->id_acceso, -3);
        $nuevoNumero = $ultimoNumero + 1;
    } else {
        // Si es el primer usuario del año, empezamos en 1
        $nuevoNumero = 1;
    }

    // 3. Formatear el número para que siempre tenga 3 dígitos (ej: 1 pasa a '001', 12 pasa a '012')
    $numeroFormateado = str_pad($nuevoNumero, 3, '0', STR_PAD_LEFT);

    // 4. Armar el ID final (Ej: UCO-2026-001)
    $idAccesoUnico = "UCO-{$anioActual}-{$numeroFormateado}";

    // 5. Guardar en la base de datos
    $usuario = new Usuario();
    $usuario->id_acceso = $idAccesoUnico;
    $usuario->nombre_completo = $request->nombre_completo;
    $usuario->correo = $request->correo;
    $usuario->contrasena = bcrypt($request->contrasena);
    $usuario->departamento = $request->departamento;
    $usuario->fecha_alta = Carbon::now()->format('Y-m-d');
    $usuario->save();

    return redirect()->back()->with('success', 'Usuario creado con el ID: ' . $idAccesoUnico);
}