<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password; // <--- Usamos la herramienta nativa

class ForgotPasswordController extends Controller
{
    // 1. Mostrar el formulario
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    // 2. Procesar el envío del correo
    public function sendResetLinkEmail(Request $request)
    {
        // Validamos que el campo email venga correctamente
        $request->validate(['email' => 'required|email']);

        // Intentamos enviar el enlace de recuperación
        $status = Password::sendResetLink(
    ['correo' => $request->email] // Aquí le decimos que busque explícitamente en 'correo'
);

        // Si se envió correctamente
        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', '¡Enlace enviado! Revisa tu correo electrónico.');
        }

        // Si falló (ej. el correo no existe en la base de datos)
        return back()->withErrors(['email' => 'No encontramos ese correo en nuestro sistema.']);
    }
}