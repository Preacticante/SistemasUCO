<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LeyVacacion;

class SettingsController extends Controller
{
    /**
     * Muestra la vista de configuración
     */
    public function index()
    {
        if (! session('logeado')) {
            return redirect()->route('login');
        }

        // Traemos las reglas de la ley de vacaciones para el modal
        $leyVacaciones = LeyVacacion::orderBy('anios_antiguedad')->get();

        // Intentamos cargar valores persistentes desde la tabla `configuraciones` si existe.
        // Si no hay registros, se seguirán usando los valores en sesión (o defaults).
        try {
            $dbConfigs = DB::table('configuraciones')
                ->whereIn('clave', ['sabados_contables', 'minimo_dias_continuos', 'meses_caducidad', 'ciclo_actual'])
                ->pluck('valor', 'clave');

            $defaults = [
                'sabados_contables' => '0',
                'minimo_dias_continuos' => '6',
                'meses_caducidad' => '18',
                'ciclo_actual' => date('Y'),
            ];

            foreach ($defaults as $clave => $valorDef) {
                if (session($clave) === null) {
                    $valor = $dbConfigs->get($clave, $valorDef);
                    session([$clave => $valor]);
                }
            }
        } catch (\Exception $e) {
            // Si la tabla no existe o hay un error de conexión, ignoramos y seguimos con sesión/valores por defecto.
        }

        return view('configuracion', compact('leyVacaciones'));
    }

    /**
     * Procesa y guarda los cambios operativos (¡Aquí está la solución!)
     */
    public function update(Request $request) 
    {
        if (! session('logeado')) {
            return redirect()->route('login');
        }

        // Validación básica de entrada
        $validated = $request->validate([
            'sabados_contables' => ['nullable', 'in:0,1',],
            'minimo_dias_continuos' => ['required', 'in:1,2,6'],
            'meses_caducidad' => ['required', 'integer', 'min:1', 'max:48'],
            'ciclo_actual' => ['required', 'integer', 'min:2000', 'max:2100'],
        ]);

        // Normalizar valores y guardar en sesión
        $sabados = $request->has('sabados_contables') ? '1' : '0';
        $minimo = $request->input('minimo_dias_continuos');
        $meses = $request->input('meses_caducidad');
        $ciclo = $request->input('ciclo_actual');

        session([
            'sabados_contables' => $sabados,
            'minimo_dias_continuos' => $minimo,
            'meses_caducidad' => $meses,
            'ciclo_actual' => $ciclo,
        ]);

        // Intentamos persistir en BD (tabla `configuraciones`). Si la tabla no existe, no fallaremos.
        try {
            foreach (['sabados_contables' => $sabados, 'minimo_dias_continuos' => $minimo, 'meses_caducidad' => $meses, 'ciclo_actual' => $ciclo] as $clave => $valor) {
                DB::table('configuraciones')->updateOrInsert(
                    ['clave' => $clave],
                    ['valor' => $valor, 'updated_at' => now(), 'created_at' => now()]
                );
            }
        } catch (\Exception $e) {
            // Registrar en log sería ideal, pero no romper la experiencia de usuario.
        }

        return redirect()->back()->with('success', '¡Configuración operativa guardada con éxito!');
    }
}