<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function showLoginForm() {
        return view('auth.login');
    }
    public function login(Request $request) {
        $request->validate([
            'correo' => 'required|email',
            'contrasena' => 'required',
        ]);

        $usuario = \App\Models\Usuario::where('correo', $request->correo)->first();
        if (! $usuario) {
            return back()->withErrors(['correo' => 'El usuario no existe'])->withInput();
        }

        if (\Illuminate\Support\Facades\Hash::check($request->contrasena, $usuario->contrasena)) {
            session(['logeado' => true, 'user_id' => $usuario->correo, 'nombre' => $usuario->nombre_completo]);
            return redirect()->route('panel');
        }

        return back()->withErrors(['contrasena' => 'Contraseña incorrecta'])->withInput();
    }
    public function logout(Request $request) {
        // lógica de logout
    }
}
