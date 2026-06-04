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

        $anioActual = Carbon::now()->year;
        
        // 2. Traemos todos los empleados activos y la tabla de leyes (de mayor a menor antigüedad)
        $empleados = Empleado::all();
        $leyes = LeyVacacion::orderBy('anios_antiguedad', 'desc')->get();

        $totalDiasDerecho = 0;
        $empleadosCalculados = collect(); // Aquí guardaremos temporalmente las matemáticas de cada empleado

        // 3. Recorremos a TODOS los empleados uno por uno para hacer sus cálculos
        foreach ($empleados as $empleado) {
            
            // Calculamos cuántos años lleva trabajando
            $antiguedadAnios = Carbon::parse($empleado->fecha_ingreso)->diffInYears(Carbon::now());
            
            // Buscamos cuántos días le tocan por ley según sus años
            $ley = $leyes->firstWhere('anios_antiguedad', '<=', $antiguedadAnios);
            $diasDerecho = $ley ? $ley->dias_derecho : 0;
            
            // Sumamos sus días al Gran Total de la empresa
            $totalDiasDerecho += $diasDerecho;

            // Buscamos cuántos días ya pidió este empleado en el año actual
            $diasTomados = RegistroDescanso::where('empleado_id', $empleado->id)
                ->where('anio_calendario', $anioActual)
                ->sum('dias_tomados');

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

        // 4. Cálculos Globales para las 4 Tarjetas Superiores
        $totalEmpleados = $empleados->count();
        
        // Suma total de días tomados por todos en toda la escuela este año
        $diasTomadosEsteAnio = RegistroDescanso::where('anio_calendario', $anioActual)->sum('dias_tomados');
        
        // El gran total de días que la escuela aún debe
        $diasRestantesTotales = max(0, $totalDiasDerecho - $diasTomadosEsteAnio);

        // 5. LÓGICA CORRECTA PARA LA TABLA DE ALERTAS (Empleados con menos días restantes)
        // - .filter(): Filtra e ignora a los empleados nuevos que tengan 0 días por derecho de ley.
        // - .sortBy(): Ordena de menor a mayor 'diasRestantes' y, en caso de empate, pone primero al que tenga más 'diasTomados'.
        // - .take(5): Muestra únicamente el Top 5 crítico.
        $empleadosConMenosDias = $empleadosCalculados
            ->filter(function ($emp) {
                return $emp->diasDerecho > 0;
            })
            ->sortBy([
                ['diasRestantes', 'asc'],
                ['diasTomados', 'desc']
            ])
            ->take(5);

        // 6. Enviamos todos los números reales a tu vista 'dashboard.blade.php'
        return view('dashboard', compact(
            'totalEmpleados',
            'totalDiasDerecho',
            'diasTomadosEsteAnio',
            'diasRestantesTotales',
            'empleadosConMenosDias'
        ));
    }
}