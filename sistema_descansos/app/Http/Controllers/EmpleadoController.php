<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class EmpleadoController extends Controller
{
    public function index() {
        // Jalamos todos los empleados de la base de datos
        $empleados = \App\Models\Empleado::with('puesto')->get();
        $puestos = \App\Models\Puesto::all();

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
                // Si es por AJAX devolvemos un JSON de error con código 404
                return response()->json(['success' => false, 'message' => 'Empleado no encontrado.'], 404);
            }

            // Actualiza de verdad en la base de datos
            $empleado->update($validated);
            
            // CAMBIO AQUÍ: Retornamos éxito en formato JSON para que JavaScript cierre el modal y actualice la tabla
            return response()->json([
                'success' => true,
                'message' => 'Empleado actualizado correctamente.',
                'id' => $empleado->id
            ]);

        } catch (\Exception $e) {
            // Si algo falla, atrapa el error y mándalo al JavaScript sin romper la web
            return response()->json([
                'success' => false, 
                'message' => 'No se pudo actualizar el empleado en la base de datos: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id) {
        try {
            $empleado = \App\Models\Empleado::find($id);
            if (! $empleado) {
                return response()->json(['success' => false, 'message' => 'Empleado no encontrado'], 404);
            }
            $empleado->delete();
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
      // 1. Obtener el año seleccionado desde el formulario (por defecto el año actual)
        $anio = $request->query('anio', \Carbon\Carbon::now()->year);

        // 2. Traer todos los empleados junto con sus puestos asignados
        $empleados = \App\Models\Empleado::with('puesto')->get();

        // 3. Arreglo con los nombres de los meses para las cabeceras de las columnas del PDF
        $meses = [
            1 => 'Ene', 2 => 'Feb', 3 => 'Mar', 4 => 'Abr', 
            5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Ago', 
            9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dic'
        ];

        $rows = [];

        foreach ($empleados as $emp) {
            // Validar que exista la fecha de ingreso antes de calcular
            if (!$emp->fecha_ingreso) {
                continue; 
            }

            $fechaIngreso = \Carbon\Carbon::parse($emp->fecha_ingreso);
            $fechaCierreAnio = \Carbon\Carbon::createFromDate($anio, 12, 31);
            
            // Antigüedad calculada en años cumplidos al cierre del año seleccionado
            $antiguedad = (int) $fechaIngreso->diffInYears($fechaCierreAnio);
            if ($antiguedad < 1) {
                $antiguedad = 1; // Ajuste mínimo de base legal si está en su primer año
            }

            // !!! CONEXIÓN CON TU TABLA DE LEY (Muestra los días de derecho correspondientes) !!!
            $ley = \DB::table('ley_vacaciones')
                        ->where('anios_antiguedad', '<=', $antiguedad)
                        ->orderBy('anios_antiguedad', 'desc')
                        ->first();

            // Si encuentra el registro en tu tabla usa la columna 'dias_derecho', si no, asigna 0 por defecto
            $diasDerecho = $ley ? $ley->dias_derecho : 0;

            // 4. Obtener el desglose mensual del empleado filtrando por la columna 'fecha_inicio'
            $descansos = \DB::table('periodos_vacacionales') 
                        ->where('empleado_id', $emp->id)
                        ->whereYear('fecha_inicio', $anio)
                        ->get();

            // Construir el desglose del mes 1 al 12
            $registroPorMes = [];
            for ($m = 1; $m <= 12; $m++) {
                $sumaMes = $descansos->filter(function($item) use ($m) {
                    return \Carbon\Carbon::parse($item->fecha_inicio)->month == $m;
                })->sum('dias'); 

                $registroPorMes[$m] = $sumaMes > 0 ? $sumaMes : 0;
            }

            // 5. Calcular días totales tomados usando tus columnas reales 'dias' y 'anio_calendario'
            $diasTomados = \DB::table('periodos_vacacionales')
                        ->where('empleado_id', $emp->id)
                        ->where('anio_calendario', $anio)
                        ->sum('dias') ?? 0;

            // 6. Calcular días restantes (Derecho por antigüedad menos Tomados)
            $diasRestantes = $diasDerecho - $diasTomados;

            // Guardar en la estructura exacta que lee tu archivo Blade
            $rows[] = [
                'empleado'       => $emp,
                'antiguedad'     => $antiguedad,
                'dias_derecho'   => $diasDerecho,
                'registroPorMes' => $registroPorMes,
                'diasTomados'    => $diasTomados,
                'diasRestantes'  => $diasRestantes
            ];
        }

        // 7. Renderizar la vista PDF masivo aplicando orientación horizontal (landscape)
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('empleados.vacaciones_all_pdf', [
            'rows'  => $rows,
            'anio'  => $anio,
            'meses' => $meses
        ])->setPaper('letter', 'landscape');

        return $pdf->stream("Vacaciones_Personal_{$anio}.pdf");
    }
}