<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use Carbon\Carbon;

class ProfileController extends Controller
{
    /**
     * Muestra la vista del perfil unificada con los contadores en tiempo real
     */
    public function show()
    {
        // 1. Validamos la sesión personalizada
        if (! session('logeado')) {
            return redirect()->route('login');
        }
        
        // 2. Buscamos al usuario por correo
        $usuario = DB::table('usuario')->where('correo', session('email'))->first();

        if (!$usuario) {
            session()->forget(['logeado', 'user_id', 'nombre', 'email']);
            return redirect()->route('login');
        }

        // Extraemos el ID numérico real del objeto (sea ID o id)
        $idRealUsuario = $usuario->id ?? ($usuario->ID ?? null);

        // ====================================================================
        // 3. CÁLCULO DE ESTADÍSTICAS PROTEGIDO CONTRA TABLAS INEXISTENTES
        // ====================================================================
        
        // --- Conteo de Empleados ---
        try {
            $empleadosACargo = DB::table('empleados')->count(); 
        } catch (\Exception $e) {
            $empleadosACargo = 0; 
        }
        
        // --- Suma de Días Gestionados ---
        try {
            // Nota: Veo en tu traza que tu tabla se llama 'periodos_vacacionales' y la columna 'dias'
            $diasGestionados = DB::table('periodos_vacacionales')->sum('dias'); 
        } catch (\Exception $e) {
            $diasGestionados = 0; 
        }
        
        // --- Conteo de Reportes ---
        try {
            // Buscamos en la tabla usando el ID numérico correcto del usuario
            $reportesGenerados = DB::table('reportes')
                                ->where('usuario_id', $idRealUsuario)
                                ->count();
        } catch (\Exception $e) {
            $reportesGenerados = 0; 
        }

        // 4. Pasamos las variables a la vista 'perfil'
        return view('perfil', compact('usuario', 'empleadosACargo', 'diasGestionados', 'reportesGenerados'));
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

        $userCorreo = session('user_id') ?? session('email');

        // 2. ACTUALIZACIÓN EN LA BASE DE DATOS usando el correo, que es la clave primaria real
        DB::table('usuario')
            ->where('correo', $userCorreo)
            ->update([
                'nombre_completo' => $request->name, 
                'correo' => $request->email,
            ]);

        // 3. Sincronizamos los nuevos datos en la sesión temporal del navegador
        session([
            'nombre' => $request->name,
            'email'  => $request->email,
            'user_id' => $request->email,
        ]);

        return redirect()->route('perfil')->with('success', '¡Perfil actualizado correctamente en la base de datos!');
    }

    /**
     * Procesa el cambio de contraseña con validación de seguridad
     */
    public function changePassword(Request $request)
    {
        // 1. Validamos campos y confirmación
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:6|confirmed', 
        ]);

        $userCorreo = session('user_id') ?? session('email');

        // 2. Buscamos al usuario por su correo de sesión
        $usuario = DB::table('usuario')->where('correo', $userCorreo)->first();

        if (! $usuario) {
            return back()->withErrors(['current_password' => 'No se encontró el usuario en sesión.']);
        }

        // 3. Verificamos que la contraseña actual ingresada coincida con la encriptada en la BD
        if (! Hash::check($request->current_password, $usuario->contrasena)) {
            return back()->withErrors(['current_password' => 'La contraseña actual ingresada es incorrecta.']);
        }

        // 4. Si todo está correcto, actualizamos la contraseña encriptada
        DB::table('usuario')
            ->where('correo', $userCorreo)
            ->update([
                'contrasena' => Hash::make($request->new_password)
            ]);

        return redirect()->route('perfil')->with('success', '¡Contraseña actualizada por seguridad!');
    }
}