<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use Carbon\Carbon; // <--- Importamos Carbon para manejar las fechas de forma nativa

class LoginController extends Controller
{
    public function showLoginForm() {
        // Si ya está logeado, lo mandamos directo al panel
        if (session('logeado')) {
            return redirect()->route('panel');
        }
        return view('auth.login');
    }

    public function login(Request $request) {
        $request->validate([
            'correo' => 'required|email',
            'contrasena' => 'required',
        ]);

        $usuario = Usuario::where('correo', $request->correo)->first();
        
        if (! $usuario) {
            return back()->withErrors(['correo' => 'El usuario no existe'])->withInput();
        }

        if (Hash::check($request->contrasena, $usuario->contrasena)) {
            
            // ====================================================================
            // REGISTRO DEL ÚLTIMO ACCESO
            // Guarda la fecha y hora exacta actual en la base de datos
            // ====================================================================
            $usuario->ultimo_acceso = Carbon::now();
            $usuario->save(); 

            // Guardamos las variables clave en la sesión
            session([
                'logeado' => true, 
                'user_id' => $usuario->id, // <-- CORREGIDO: Guardamos el ID numérico real (ej: 1, 2)
                'nombre'  => $usuario->nombre_completo,
                'email'   => $usuario->correo 
            ]);
            
            return redirect()->route('panel');
        }

        return back()->withErrors(['contrasena' => 'Contraseña incorrecta'])->withInput();
    }

    public function logout(Request $request) {
        // Limpiamos todo el rastro de la sesión al salir
        session()->forget(['logeado', 'user_id', 'nombre', 'email']);
        return redirect()->route('login');
    }
}