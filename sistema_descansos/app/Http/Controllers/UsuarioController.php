<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    // 1. Método para listar los usuarios en la pantalla de forma dinámica
    public function list() 
    {
        $usuarios = Usuario::select('id', 'id_acceso', 'nombre_completo', 'correo', 'departamento')->get();
        return response()->json($usuarios);
    }

    // 2. Tu método store adaptado para responder a FETCH AJAX
    public function store(Request $request) 
    {
        $anioActual = Carbon::now()->year; 

        $ultimoUsuario = Usuario::where('id_acceso', 'LIKE', "UCO-{$anioActual}-%")
                                ->orderBy('id_acceso', 'desc')
                                ->first();

        if ($ultimoUsuario) {
            $ultimoNumero = (int) substr($ultimoUsuario->id_acceso, -3);
            $nuevoNumero = $ultimoNumero + 1;
        } else {
            $nuevoNumero = 1;
        }

        $numeroFormateado = str_pad($nuevoNumero, 3, '0', STR_PAD_LEFT);
        $idAccesoUnico = "UCO-{$anioActual}-{$numeroFormateado}";

        $usuario = new Usuario();
        $usuario->id_acceso = $idAccesoUnico;
        $usuario->nombre_completo = $request->nombre_completo;
        $usuario->correo = $request->correo;
        $usuario->contrasena = bcrypt($request->contrasena);
        $usuario->departamento = $request->departamento;
        $usuario->fecha_alta = Carbon::now()->format('Y-m-d');
        $usuario->save();

        // RETORNO JSON EN LUGAR DE REDIRECT BACK
        return response()->json([
            'success' => true,
            'message' => 'Usuario creado con éxito. ID: ' . $idAccesoUnico
        ]);
    }

    // 3. Método opcional para editar/actualizar
    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->nombre_completo = $request->nombre_completo;
        $usuario->correo = $request->correo;
        $usuario->departamento = $request->departamento;
        
        if ($request->filled('contrasena')) {
            $usuario->contrasena = bcrypt($request->contrasena);
        }
        
        $usuario->save();

        return response()->json(['success' => true, 'message' => 'Usuario actualizado con éxito']);
    }

    // 4. Método opcional para eliminar
    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->delete();

        return response()->json(['success' => true, 'message' => 'Usuario eliminado correctamente']);
    }
}