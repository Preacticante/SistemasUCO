<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use App\Models\LeyVacacion;
use App\Models\RegistroDescanso;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index() {
        $anioActual = Carbon::now()->year;
        $empleados = Empleado::all();
        $totalEmpleados = $empleados->count();

        // Calcular días de derecho totales
        $antiguedades = $empleados->map(function($e) { return Carbon::parse($e->fecha_ingreso)->diffInYears(Carbon::now()); });
        $leyes = LeyVacacion::all()->keyBy('anios_antiguedad');
        $totalDiasDerecho = 0;
        foreach ($empleados as $empleado) {
            $antiguedad = Carbon::parse($empleado->fecha_ingreso)->diffInYears(Carbon::now());
            $ley = $leyes->where('anios_antiguedad', '<=', $antiguedad)->sortByDesc('anios_antiguedad')->first();
            $totalDiasDerecho += $ley?->dias_derecho ?? 0;
        }

        // Calcular días tomados y restantes
        $registros = RegistroDescanso::where('anio_calendario', $anioActual)->get();
        $totalDiasTomados = $registros->sum('dias_tomados');
        $totalDiasRestantes = $totalDiasDerecho - $totalDiasTomados;

        // Empleados con menos días restantes
        $empleadosConMenosDias = $empleados->map(function($empleado) use ($anioActual, $leyes) {
            $antiguedad = Carbon::parse($empleado->fecha_ingreso)->diffInYears(Carbon::now());
            $ley = $leyes->where('anios_antiguedad', '<=', $antiguedad)->sortByDesc('anios_antiguedad')->first();
            $diasDerecho = $ley?->dias_derecho ?? 0;
            $registros = RegistroDescanso::where('empleado_id', $empleado->id)
                ->where('anio_calendario', $anioActual)
                ->get();
            $diasTomados = $registros->sum('dias_tomados');
            $diasRestantes = max(0, $diasDerecho - $diasTomados);
            return (object)[
                'empleado' => $empleado,
                'diasRestantes' => $diasRestantes,
                'diasTomados' => $diasTomados
            ];
        })->sortBy('diasRestantes')->take(5);

        return view('dashboard', compact(
            'totalEmpleados',
            'totalDiasDerecho',
            'totalDiasTomados',
            'totalDiasRestantes',
            'empleados',
            'empleadosConMenosDias'
        ));
    }
}
