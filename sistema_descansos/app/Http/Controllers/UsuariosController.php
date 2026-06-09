<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UsuariosController extends Controller
{
    // 1. LISTAR: Trae todos los usuarios y simula el "id" con "id_acceso" para no romper el JS
    public function list() 
    {
        try {
            // Traemos todos los registros de tu tabla
            $usuariosRaw = Usuario::select('id_acceso', 'nombre_completo', 'correo', 'departamento')->get();
            
            // Transformación de compatibilidad para el JavaScript de tu vista
            $usuarios = $usuariosRaw->map(function($u) {
                return [
                    'id'              => $u->id_acceso, // El JS lo usará como identificador en los formularios
                    'id_acceso'       => $u->id_acceso,
                    'nombre_completo' => $u->nombre_completo,
                    'correo'          => $u->correo,
                    'departamento'    => $u->departamento
                ];
            });
                                
            return response()->json($usuarios);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // 2. CREAR: Creación limpia confiando en el Folio Automático de tu modelo
    public function store(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'nombre_completo' => 'required|string|max:255',
            'correo'          => 'required|email|unique:usuario,correo',
            'departamento'    => 'required|string|max:255',
            'contrasena'      => 'required|min:4',
        ], [
            'correo.unique' => 'Este correo electrónico ya está registrado.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $usuario = new Usuario();
            $usuario->nombre_completo = $request->nombre_completo;
            $usuario->correo          = $request->correo;
            $usuario->contrasena      = bcrypt($request->contrasena);
            $usuario->departamento    = $request->departamento;
            $usuario->fecha_alta      = Carbon::now()->format('Y-m-d');
            
            // Tu modelo ejecutará el evento static::creating() aquí y asignará el id_acceso solo
            $usuario->save(); 

            return response()->json([
                'success' => true,
                'message' => 'Usuario registrado con éxito.'
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // 3. EDITAR / ACTUALIZAR: Busca de forma segura por el folio 'id_acceso'
    public function update(Request $request, $id_acceso)
    {
        $validator = Validator::make($request->all(), [
            'nombre_completo' => 'required|string|max:255',
            'correo'          => 'required|email',
            'departamento'    => 'required|string|max:255',
            'contrasena'      => 'nullable|min:4',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            // Buscamos al usuario por su folio único institucional
            $usuario = Usuario::where('id_acceso', $id_acceso)->first();

            if (!$usuario) {
                return response()->json(['success' => false, 'message' => 'Usuario no encontrado.'], 404);
            }

            $usuario->nombre_completo = $request->nombre_completo;
            $usuario->correo          = $request->correo;
            $usuario->departamento    = $request->departamento;

            if ($request->filled('contrasena')) {
                $usuario->contrasena = bcrypt($request->contrasena);
            }

            $usuario->save();

            return response()->json(['success' => true, 'message' => 'Usuario actualizado correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // 4. ELIMINAR: Borrado físico directo para evitar errores de columnas faltantes
    public function destroy($id_acceso)
    {
        try {
            $usuario = Usuario::where('id_acceso', $id_acceso)->first();

            if (!$usuario) {
                return response()->json(['success' => false, 'message' => 'Usuario no encontrado.'], 404);
            }

            // Eliminación física real de la base de datos
            $usuario->delete();

            return response()->json(['success' => true, 'message' => 'Usuario eliminado correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}