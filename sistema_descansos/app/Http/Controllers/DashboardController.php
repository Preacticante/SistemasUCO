<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

// Importamos los modelos necesarios para leer la base de datos
use App\Models\Empleado;
use App\Models\LeyVacacion;
use App\Models\RegistroDescanso;

class DashboardController extends Controller
{
    public function index()
        {
            // 1. Verificación de seguridad: Si no está logeado, lo mandamos al login
            if (! session('logeado')) {
                return redirect()->route('login');
            }

            // --- CLAVE PARA LA INDIVIDUALIZACIÓN ---
            $usuarioId = session('user_id');
            $anioActual = Carbon::now()->year; 
            
            // 2. Traemos SOLO los empleados ACTIVOS de este usuario logeado
            $empleados = Empleado::where('usuario_id', $usuarioId)
                                ->whereNull('deleted_at') // <-- Agregado para limpiar el foreach
                                ->get();
                                
            $leyes = LeyVacacion::orderBy('anios_antiguedad', 'desc')->get();

            $totalDiasDerecho = 0;
            $totalDiasTomadosUsuario = 0; 
            $empleadosCalculados = collect(); 

            // 3. Recorremos a los empleados asignados
            foreach ($empleados as $empleado) {
                $antiguedadAnios = Carbon::parse($empleado->fecha_ingreso)->diffInYears(Carbon::now());
                
                $ley = $leyes->firstWhere('anios_antiguedad', '<=', $antiguedadAnios);
                $diasExtra = DB::table('ajustes_dias_vacaciones')->where('empleado_id', $empleado->id)->where('anio', '<=', $anioActual)->sum('dias');
                $diasDerecho = ($ley ? $ley->dias_derecho : 0) + $diasExtra;
                
                $totalDiasDerecho += $diasDerecho;

                $diasTomados = RegistroDescanso::where('empleado_id', $empleado->id)
                    ->where('anio_calendario', $anioActual)
                    ->sum('dias_tomados');

                $totalDiasTomadosUsuario += $diasTomados;
                $diasRestantes = max(0, $diasDerecho - $diasTomados);

                $empleadosCalculados->push((object)[
                    'empleado'      => $empleado,
                    'diasDerecho'   => $diasDerecho,
                    'diasTomados'   => $diasTomados,
                    'diasRestantes' => $diasRestantes
                ]);
            }

            // 4. Cálculos Individuales para las Tarjetas Superiores (CORREGIDO)
                $totalEmpleados = \App\Models\Empleado::where('usuario_id', $usuarioId)
                                ->whereNull('deleted_at')
                                ->count();
            
            $diasTomadosEsteAnio = $totalDiasTomadosUsuario; 
            $diasRestantesTotales = max(0, $totalDiasDerecho - $diasTomadosEsteAnio);

            // 5. LÓGICA PARA LA TABLA DE ALERTAS
            $empleadosConMenosDias = $empleadosCalculados
                ->filter(function ($emp) {
                    return $emp->diasDerecho > 0;
                })
                ->sortBy([
                    ['diasRestantes', 'asc'],
                    ['diasTomados', 'desc']
                ])
                ->take(5);

            // 6. Enviamos todos los números reales segmentados a tu vista
            return view('dashboard', compact(
                'totalEmpleados',
                'totalDiasDerecho',
                'diasTomadosEsteAnio',
                'diasRestantesTotales',
                'empleadosConMenosDias'
            ));
        }
}