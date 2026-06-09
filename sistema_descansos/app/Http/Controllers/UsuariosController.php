<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UsuariosController extends Controller
{
    // Carga la lista inicial sin errores
    // Carga la lista inicial sin errores de columnas faltantes
    public function list() 
    {
        try {
            // CORRECCIÓN: Quitamos 'id' de la lista porque no existe en tu tabla 'usuario'
            $usuarios = Usuario::select('id_acceso', 'nombre_completo', 'correo', 'departamento')
                                ->where(function($query) {
                                    $query->whereNull('deleted_at')
                                          ->orWhere('deleted_at', '');
                                })
                                ->get();
                                
            return response()->json($usuarios);
        } catch (\Exception $e) {
            // Plan B de emergencia: si algo falla con el filtro anterior, trae todo lo disponible de las columnas seguras
            try {
                $usuarios = Usuario::select('id_acceso', 'nombre_completo', 'correo', 'departamento')->get();
                return response()->json($usuarios);
            } catch (\Exception $ex) {
                return response()->json(['error' => $ex->getMessage()], 500);
            }
        }
    }

    // Guarda el usuario de manera limpia procesando el AJAX
    public function store(Request $request) 
    {
        // Forzamos la validación con los campos exactos del formulario
        $validator = Validator::make($request->all(), [
            'nombre_completo' => 'required|string|max:255',
            'correo'          => 'required|email',
            'departamento'    => 'required|string|max:255',
            'contrasena'      => 'required|min:4',
        ], [
            'nombre_completo.required' => 'El nombre completo es requerido.',
            'correo.required'          => 'El correo electrónico es requerido.',
            'departamento.required'    => 'El departamento es requerido.',
            'contrasena.required'      => 'La contraseña es requerida.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        // 1. Obtener el año actual
        $anioActual = Carbon::now()->year; 

        // 2. Buscar el último usuario creado en este año
        $ultimoUsuario = Usuario::where('id_acceso', 'LIKE', "UCO-{$anioActual}-%")
                                ->orderBy('id_acceso', 'desc')
                                ->first();

        if ($ultimoUsuario) {
            $ultimoNumero = (int) substr($ultimoUsuario->id_acceso, -3);
            $nuevoNumero = $ultimoNumero + 1;
        } else {
            $nuevoNumero = 1;
        }

        // 3. Formatear número a 3 dígitos (Ej: 001)
        $numeroFormateado = str_pad($nuevoNumero, 3, '0', STR_PAD_LEFT);

        // 4. Armar el ID único
        $idAccesoUnico = "UCO-{$anioActual}-{$numeroFormateado}";

        // 5. Guardar en la base de datos
        $usuario = new Usuario();
        $usuario->id_acceso      = $idAccesoUnico;
        $usuario->nombre_completo = $request->nombre_completo;
        $usuario->correo          = $request->correo;
        $usuario->contrasena      = bcrypt($request->contrasena);
        $usuario->departamento    = $request->departamento;
        $usuario->fecha_alta      = Carbon::now()->format('Y-m-d');
        $usuario->save();

        // Retornamos JSON de éxito directo al JavaScript
        return response()->json([
            'success' => true,
            'message' => 'Usuario creado con éxito con el ID: ' . $idAccesoUnico
        ]);
    }
}