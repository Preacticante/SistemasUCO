<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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
        // Obtener el ID único del usuario administrador que acaba de iniciar sesión
        $usuarioId = session('user_id');
        $anioActual = Carbon::now()->year; 
        
        // 2. Traemos SOLO los empleados que pertenezcan o estén a cargo de este usuario logeado
        $empleados = Empleado::where('usuario_id', $usuarioId)->get();
        $leyes = LeyVacacion::orderBy('anios_antiguedad', 'desc')->get();

        $totalDiasDerecho = 0;
        $totalDiasTomadosUsuario = 0; // Acumulador para los días que gestionó esta cuenta activa
        $empleadosCalculados = collect(); // Aquí guardaremos temporalmente las matemáticas de cada empleado

        // 3. Recorremos a los empleados asignados a este usuario uno por uno para hacer sus cálculos
        foreach ($empleados as $empleado) {
            
            // Calculamos cuántos años lleva trabajando
            $antiguedadAnios = Carbon::parse($empleado->fecha_ingreso)->diffInYears(Carbon::now());
            
            // Buscamos cuántos días le tocan por ley según sus años
            $ley = $leyes->firstWhere('anios_antiguedad', '<=', $antiguedadAnios);
            $diasDerecho = $ley ? $ley->dias_derecho : 0;
            
            // Sumamos sus días al total de la gestión de este usuario
            $totalDiasDerecho += $diasDerecho;

            // Buscamos cuántos días ya pidió este empleado en el año actual
            $diasTomados = RegistroDescanso::where('empleado_id', $empleado->id)
                ->where('anio_calendario', $anioActual)
                ->sum('dias_tomados');

            // Sumamos al acumulador de días tomados bajo la gestión de este usuario
            $totalDiasTomadosUsuario += $diasTomados;

            // Calculamos cuántos le sobran
            $diasRestantes = max(0, $diasDerecho - $diasTomados);

            // Guardamos a este empleado en nuestra lista para la tabla inferior
            $empleadosCalculados->push((object)[
                'empleado'      => $empleado,
                'diasDerecho'   => $diasDerecho,
                'diasTomados'   => $diasTomados,
                'diasRestantes' => $diasRestantes
            ]);
        }

        // 4. Cálculos Individuales para las Tarjetas Superiores del Usuario Logeado
        $totalEmpleados = $empleados->count(); // Empleados a cargo de esta cuenta
        
        // Días totales gestionados por este usuario este año en sus empleados asignados
        $diasTomadosEsteAnio = $totalDiasTomadosUsuario; 
        
        // Total de días restantes que le quedan pendientes por otorgar exclusivamente a este usuario
        $diasRestantesTotales = max(0, $totalDiasDerecho - $diasTomadosEsteAnio);

        // 5. LÓGICA CORRECTA PARA LA TABLA DE ALERTAS (Filtrado del Top 5 de su propio grupo)
        $empleadosConMenosDias = $empleadosCalculados
            ->filter(function ($emp) {
                return $emp->diasDerecho > 0;
            })
            ->sortBy([
                ['diasRestantes', 'asc'],
                ['diasTomados', 'desc']
            ])
            ->take(5);

        // 6. Enviamos todos los números reales segmentados a tu vista 'dashboard.blade.php'
        return view('dashboard', compact(
            'totalEmpleados',
            'totalDiasDerecho',
            'diasTomadosEsteAnio',
            'diasRestantesTotales',
            'empleadosConMenosDias'
        ));
    }
}