<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;

class ResetPasswordController extends Controller
{
    // 1. Mostrar el formulario para escribir la nueva contraseña
    public function showResetForm(Request $request, $token = null)
    {
        // Asegúrate de que el nombre de esta vista coincida con tu archivo
        return view('auth.reset-password')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    
    public function reset(Request $request)
    {
        // Validamos los datos que vienen del formulario
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed', 
        ]);

        // 👈 AQUÍ ESTÁ EL TRUCO: Creamos un arreglo manual para mapear 'email' a 'correo'
        $credenciales = [
            'correo'                => $request->email, 
            'password'              => $request->password,
            'password_confirmation' => $request->password_confirmation,
            'token'                 => $request->token,
        ];

        // Intentamos restablecer usando nuestras credenciales traducidas
        $status = Password::reset(
            $credenciales,
            function ($usuario, $password) {
                // Guardamos en la columna 'contrasena'
                $usuario->contrasena = Hash::make($password);
                $usuario->save();

                event(new \Illuminate\Auth\Events\PasswordReset($usuario));
            }
        );

        // Redirigimos dependiendo del resultado
        return $status === Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', '¡Tu contraseña ha sido restablecida exitosamente!')
                    : back()->withErrors(['email' => 'No se pudo restablecer la contraseña. El enlace es inválido o expiró.']);
    }
}