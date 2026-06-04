<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Models\Puesto;

class EmpleadoController extends Controller
{
    
    public function index(Request $request) 
    {
        // 1. Capturar el término de búsqueda
        $buscar = $request->input('buscar');

        // 2. Iniciar la consulta base y conservar el filtro de deleted_at que tenías antes
        $query = \App\Models\Empleado::with('puesto')->whereNull('deleted_at');

        // 3. Si el usuario escribió algo en el buscador, aplicar los filtros con LIKE
        if (!empty($buscar)) {
            $query->where(function($q) use ($buscar) {
                $q->where('nombre', 'LIKE', "%{$buscar}%")
                  ->orWhere('apellido_paterno', 'LIKE', "%{$buscar}%")
                  ->orWhere('apellido_materno', 'LIKE', "%{$buscar}%");
            });
        }

        // 4. Paginar los resultados automáticamente (10 por página)
        $empleados = $query->paginate(10);

        // 5. Traer todos los puestos para los Modales
        $puestos = \App\Models\Puesto::all();

        // 6. Retornar la vista
        return view('empleados.index', compact('empleados', 'puestos'));
    }

    public function show($id) {
        // lógica para mostrar un empleado
    }

    public function create() {
        // lógica para mostrar formulario de creación
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'nombre' => 'required|string|max:191',
            'apellido_paterno' => 'required|string|max:191',
            'apellido_materno' => 'nullable|string|max:191',
            'fecha_ingreso' => 'required|date',
            'puesto_id' => 'required|exists:puestos,id',
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'apellido_paterno.required' => 'El apellido paterno es obligatorio.',
            'fecha_ingreso.required' => 'La fecha de ingreso es obligatoria.',
            'puesto_id.required' => 'Selecciona un puesto.',
            'puesto_id.exists' => 'El puesto seleccionado no existe.',
        ]);

        try {
            \App\Models\Empleado::create($validated);
            return redirect()->route('empleados.index')->with('success', 'Empleado creado correctamente.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'No se pudo crear el empleado: ' . $e->getMessage()]);
        }
    }

    public function edit($id) {
        // lógica para mostrar formulario de edición
    }

    /**
     * ACTUALIZACIÓN ADAPTADA PARA PETICIONES EMERGENTES (AJAX / FETCH)
     */
    public function update(Request $request, $id) {
        $validated = $request->validate([
            'nombre' => 'required|string|max:191',
            'apellido_paterno' => 'required|string|max:191',
            'apellido_materno' => 'nullable|string|max:191',
            'fecha_ingreso' => 'required|date',
            'puesto_id' => 'required|exists:puestos,id',
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'apellido_paterno.required' => 'El apellido paternal es obligatorio.',
            'fecha_ingreso.required' => 'La fecha de ingreso es obligatoria.',
            'puesto_id.required' => 'Selecciona un puesto.',
            'puesto_id.exists' => 'El puesto seleccionado no existe.',
        ]);

        try {
            $empleado = \App\Models\Empleado::find($id);
            if (! $empleado) {
                return response()->json(['success' => false, 'message' => 'Empleado no encontrado.'], 404);
            }

            $empleado->update($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Empleado actualizado correctamente.',
                'id' => $empleado->id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'No se pudo actualizar el empleado en la base de datos: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id) {
        try {
            $empleado = \DB::table('empleados')->where('id', $id)->first();
            
            if (!$empleado) {
                return response()->json(['success' => false, 'message' => 'Empleado no encontrado'], 404);
            }
            
            \DB::table('empleados')
                ->where('id', $id)
                ->update([
                    'deleted_at' => \Carbon\Carbon::now()
                ]);
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Genera el reporte PDF masivo de vacaciones de todos los empleados
     */
    public function pdfAll(Request $request)
    {
        $anio = $request->query('anio', \Carbon\Carbon::now()->year);
        $empleados = \App\Models\Empleado::with('puesto')->get();

        $meses = [
            1 => 'Ene', 2 => 'Feb', 3 => 'Mar', 4 => 'Abr', 
            5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Ago', 
            9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dic'
        ];

        $rows = [];

        foreach ($empleados as $emp) {
            if (!$emp->fecha_ingreso) {
                continue; 
            }

            $fechaIngreso = \Carbon\Carbon::parse($emp->fecha_ingreso);
            $fechaCierreAnio = \Carbon\Carbon::createFromDate($anio, 12, 31);
            
            $antiguedad = (int) $fechaIngreso->diffInYears($fechaCierreAnio);
            if ($antiguedad < 1) {
                $antiguedad = 1;
            }

            $ley = \DB::table('ley_vacaciones')
                        ->where('anios_antiguedad', '<=', $antiguedad)
                        ->orderBy('anios_antiguedad', 'desc')
                        ->first();

            $diasDerecho = $ley ? $ley->dias_derecho : 0;

            $descansos = \DB::table('periodos_vacacionales') 
                        ->where('empleado_id', $emp->id)
                        ->whereYear('fecha_inicio', $anio)
                        ->get();

            $registroPorMes = [];
            for ($m = 1; $m <= 12; $m++) {
                $sumaMes = $descansos->filter(function($item) use ($m) {
                    return \Carbon\Carbon::parse($item->fecha_inicio)->month == $m;
                })->sum('dias'); 

                $registroPorMes[$m] = $sumaMes > 0 ? $sumaMes : 0;
            }

            $diasTomados = \DB::table('periodos_vacacionales')
                        ->where('empleado_id', $emp->id)
                        ->where('anio_calendario', $anio)
                        ->sum('dias') ?? 0;

            $diasRestantes = $diasDerecho - $diasTomados;

            $rows[] = [
                'empleado'       => $emp,
                'antiguedad'     => $antiguedad,
                'dias_derecho'   => $diasDerecho,
                'registroPorMes' => $registroPorMes,
                'diasTomados'    => $diasTomados,
                'diasRestantes'  => $diasRestantes
            ];
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('empleados.vacaciones_all_pdf', [
            'rows'  => $rows,
            'anio'  => $anio,
            'meses' => $meses
        ])->setPaper('letter', 'landscape');

        return $pdf->stream("Vacaciones_Personal_{$anio}.pdf");
    }
}