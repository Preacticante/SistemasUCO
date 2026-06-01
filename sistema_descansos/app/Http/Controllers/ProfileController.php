<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // 1. ASEGÚRATE DE IMPORTAR EL FACADE DB AQUÍ ARRIBA

class ProfileController extends Controller
{
    public function show()
    {
        if (! session('logeado')) {
            return redirect()->route('login');
        }
        return view('perfil');
    }

    public function update(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        // 2. GUARDAR EN LA BASE DE DATOS (Para que lo vea tu compañero)
        // NOTA: Cambia 'users' por el nombre exacto de tu tabla de administradores si se llama distinto (ej. 'usuarios')
        // Y si guardas el ID del admin en la sesión, cambia el '1' por session('user_id')
        DB::table('users')
            ->where('id', session('user_id', 1)) 
            ->update([
                'name'  => $request->name,
                'email' => $request->email,
            ]);

        // 3. ACTUALIZAR TU SESIÓN LOCAL (Para que lo veas tú de inmediato)
        session([
            'nombre' => $request->name,
            'email'  => $request->email
        ]);

        return redirect()->route('perfil')->with('success', '¡Perfil actualizado en el sistema correctamente!');
    }
}