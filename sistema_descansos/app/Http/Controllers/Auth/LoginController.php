<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

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
            // Guardamos las variables clave en la sesión
            session([
                'logeado' => true, 
                'user_id' => $usuario->id, // Guardamos el ID numérico real para los queries
                'nombre' => $usuario->nombre_completo,
                'email' => $usuario->correo // <--- Agregado para que tu perfil sea 100% dinámico
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