<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Muestra la vista del perfil
     */
    public function show()
    {
        if (! session('logeado')) {
            return redirect()->route('login');
        }
        
        return view('perfil');
    }

    /**
     * Procesa la actualización del nombre y correo en BD y Sesión
     */
    public function update(Request $request)
    {
        // 1. Validamos los campos del formulario
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        $correoActual = session('email');

        // 2. ACTUALIZACIÓN EN LA BASE DE DATOS
        // Aquí cambiamos 'email' por 'correo' basándonos en tu base de datos
       // 2. ACTUALIZACIÓN EN LA BASE DE DATOS
        DB::table('usuario')
            ->where('correo', $correoActual)
            ->update([
                'nombre_completo' => $request->name, // <-- Cambia esto por el nombre exacto de tu BD
                'correo' => $request->email,
            ]);

        // 3. Guardamos los nuevos datos en la sesión temporal del navegador
        session([
            'nombre' => $request->name,
            'email'  => $request->email
        ]);

        return redirect()->route('perfil')->with('success', '¡Perfil actualizado correctamente en la base de datos!');
    }

    /**
     * Procesa el cambio de contraseña
     */
    /**
     * Procesa el cambio de contraseña
     */
    public function changePassword(Request $request)
    {
        // 1. Validamos que los campos vengan llenos y que la nueva contraseña coincida con su confirmación
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:6|confirmed', 
        ]);

        $correoActual = session('email');

        // 2. Buscamos los datos actuales del usuario en la base de datos
        $usuario = DB::table('usuario')->where('correo', $correoActual)->first();

        // 3. Verificamos que la contraseña actual ingresada coincida con la encriptada en la BD
        // OJO: Cambia 'contrasena' por 'password' si así se llama en tu base de datos
        if (!Hash::check($request->current_password, $usuario->contrasena)) {
            // Si no coincide, lo regresamos con un error
            return back()->withErrors(['current_password' => 'La contraseña actual ingresada es incorrecta.']);
        }

        // 4. Si todo está correcto, actualizamos la base de datos con la nueva contraseña encriptada
        DB::table('usuario')
            ->where('correo', $correoActual)
            ->update([
                'contrasena' => Hash::make($request->new_password) // <-- Ojo aquí con el nombre de la columna también
            ]);

        // 5. Regresamos con el mensaje de éxito
        return redirect()->route('perfil')->with('success', '¡Contraseña actualizada por seguridad!');
    }
}