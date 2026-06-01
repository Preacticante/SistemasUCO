<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
     * Procesa la actualización del nombre y correo (¡La función que nos falta!)
     */
    public function update(Request $request)
    {
        // 1. Validamos que los datos cumplan con los requisitos básicos
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        // 2. Guardamos los nuevos datos en la sesión para que se actualice la interfaz
        session([
            'nombre' => $request->name,
            'email'  => $request->email
        ]);

        // 3. EL REBOTE CRÍTICO: Redireccionamos a la ruta 'perfil' con un mensaje de éxito
        // Esto es lo que elimina la pantalla blanca y te regresa de golpe
        return redirect()->route('perfil')->with('success', '¡Perfil actualizado correctamente!');
    }
}