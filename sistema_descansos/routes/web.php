<?php

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

// Modelos
use App\Models\Empleado;
use App\Models\LeyVacacion;
use App\Models\RegistroDescanso;
use App\Models\PeriodoVacacional;
use App\Models\Puesto;

// Controladores
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\PuestoController;

// Librerías
use Dompdf\Dompdf;


// | RUTAS DE AUTENTICACIÓN

Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
Route::post('/puestos', [PuestoController::class, 'store'])->name('puestos.store');
Route::delete('/puestos/{id}', [PuestoController::class, 'destroy'])->name('puestos.destroy');
Route::post('/puestos/store', [App\Http\Controllers\EmpleadoController::class, 'storePuesto'])->name('puestos.store');

Route::get('/logout', function () {
    session()->forget(['logeado', 'user_id', 'nombre', 'email']);
    return redirect()->route('login');
})->name('logout');


// | RUTAS DEL MENÚ PRINCIPAL (SIDEBAR)

Route::get('/panel', [DashboardController::class, 'index'])->name('panel');
Route::resource('empleados', EmpleadoController::class);

Route::get('/directorio-empleados', function () {
    if (! session('logeado')) return redirect()->route('login');
    return redirect()->route('empleados.index');
})->name('empleados');

Route::get('/historial', function (Request $request) {
    if (! session('logeado')) return redirect()->route('login');

    $buscar = $request->input('buscar');
    $query = PeriodoVacacional::with('empleado')
        ->whereNull('deleted_at')
        ->orderBy('fecha_inicio', 'desc');

    if ($buscar) {
        $query->whereHas('empleado', function ($q) use ($buscar) {
            $q->where('nombre', 'LIKE', "%{$buscar}%")
              ->orWhere('apellido_paterno', 'LIKE', "%{$buscar}%")
              ->orWhere('apellido_materno', 'LIKE', "%{$buscar}%");
        });
    }

    $periodosVacacionales = $query->paginate(10)->withQueryString();

    return view('historial', compact('periodosVacacionales', 'buscar'));
})->name('historial');

Route::get('/configuracion', [SettingsController::class, 'index'])->name('configuracion');
Route::post('/configuracion/update', [SettingsController::class, 'update'])->name('configuracion.update');

Route::get('/perfil', [ProfileController::class, 'show'])->name('perfil');
Route::get('/perfiles', function () {
    if (! session('logeado')) return redirect()->route('login');
    return view('usuarios.index'); 
})->name('perfiles.index');

Route::get('/perfiles/list', [UsuariosController::class, 'list'])->name('perfiles.list');
Route::post('/perfiles', [UsuariosController::class, 'store'])->name('perfiles.store');
Route::put('/perfiles/{id}', [UsuariosController::class, 'update'])->name('perfiles.update');
Route::delete('/perfiles/{id}', [UsuariosController::class, 'destroy'])->name('perfiles.destroy');
Route::post('/perfil/update', [ProfileController::class, 'update'])->name('perfil.update');
Route::post('/perfil/password', [ProfileController::class, 'changePassword'])->name('perfil.password');
Route::post('/puestos', [App\Http\Controllers\PuestoController::class, 'store'])->name('puestos.store');


// | RUTAS DE LÓGICA DE VACACIONES (MÓDULO INDIVIDUAL)

Route::get('/empleados/{empleado}/vacaciones', function (Empleado $empleado) {
    if (! session('logeado')) return redirect()->route('login');

    $anioActual = Carbon::now()->year;
    // CORRECCIÓN: Forzamos a que la antigüedad sea un número entero para evitar decimales infinitos
    $antiguedadAnios = (int) floor(Carbon::parse($empleado->fecha_ingreso)->diffInYears(Carbon::now()));
    $ley = LeyVacacion::where('anios_antiguedad', '<=', $antiguedadAnios)->orderBy('anios_antiguedad', 'desc')->first();
    $diasDerecho = $ley?->dias_derecho ?? 0;

    $registros = RegistroDescanso::where('empleado_id', $empleado->id)->where('anio_calendario', $anioActual)->orderBy('mes')->get();
    $diasTomados = $registros->sum('dias_tomados');
    $diasRestantes = max(0, $diasDerecho - $diasTomados);

    $meses = [
        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
        5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
        9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
    ];

    $registroPorMes = [];
    foreach ($meses as $numero => $nombre) {
        $registroPorMes[$numero] = $registros->firstWhere('mes', $numero)?->dias_tomados ?? 0;
    }

    // CORRECCIÓN: Buscamos el nombre del puesto para enviarlo a la vista
    $puestoNombre = $empleado->puesto_id ? Puesto::find($empleado->puesto_id)?->nombre : 'No asignado';

    return view('empleados.vacaciones', compact('empleado', 'anioActual', 'antiguedadAnios', 'diasDerecho', 'diasTomados', 'diasRestantes', 'meses', 'registroPorMes', 'puestoNombre'));
})->name('empleados.vacaciones');

Route::post('/empleados/{empleado}/vacaciones', function (Request $request, Empleado $empleado) {
    if (! session('logeado')) return redirect()->route('login');

    $anioActual = Carbon::now()->year;
    $antiguedadAnios = (int) floor(Carbon::parse($empleado->fecha_ingreso)->diffInYears(Carbon::now()));
    $ley = LeyVacacion::where('anios_antiguedad', '<=', $antiguedadAnios)->orderBy('anios_antiguedad', 'desc')->first();
    $diasDerecho = $ley?->dias_derecho ?? 0;

    $validator = Validator::make($request->all(), [
        'multiple_dates'   => 'required|string',
        'fecha_inicio'     => 'required|date',
        'fecha_fin'        => 'required|date|after_or_equal:fecha_inicio',
        'dias_solicitados' => 'required|integer|min:1',
        'observaciones'    => 'nullable|string|max:1000', // <-- ADICIONADO EN LA VALIDACIÓN
    ]);

    if ($validator->fails()) return redirect()->back()->withErrors($validator)->withInput();
    
    $selectedDates = array_filter(array_map('trim', explode(',', $request->input('multiple_dates'))));
    if (empty($selectedDates)) {
        return back()->withErrors(['multiple_dates' => 'Selecciona al menos un día en el calendario.'])->withInput();
    }

    sort($selectedDates);
    $inicio = Carbon::parse($selectedDates[0]);
    $fin = Carbon::parse(end($selectedDates));

    if ($inicio->year !== $anioActual || $fin->year !== $anioActual) return back()->withErrors(['fecha_inicio' => "Las fechas deben estar dentro del año {$anioActual}."])->withInput();

    $registroPorMes = RegistroDescanso::where('empleado_id', $empleado->id)->where('anio_calendario', $anioActual)->get();
    $diasTomadosActuales = $registroPorMes->sum('dias_tomados');
    
    $diasNuevos = count($selectedDates);

    if ($diasTomadosActuales + $diasNuevos > $diasDerecho) {
        return back()->withErrors(['fecha_inicio' => "El empleado no cuenta con suficientes días. Has solicitado {$diasNuevos} días."])->withInput();
    }

    $diasPorMes = [];
    foreach ($selectedDates as $selectedDate) {
        $fecha = Carbon::parse($selectedDate);
        if ($fecha->year !== $anioActual) {
            return back()->withErrors(['fecha_inicio' => "Las fechas deben estar dentro del año {$anioActual}."])->withInput();
        }

        $mes = $fecha->month;
        $diasPorMes[$mes] = ($diasPorMes[$mes] ?? 0) + 1;
    }

    $diasPeriodo = $diasNuevos;

    try {
        DB::transaction(function () use ($diasPorMes, $empleado, $anioActual, $inicio, $fin, $request, $diasPeriodo) {
            foreach ($diasPorMes as $mes => $cantidad) {
                $registro = RegistroDescanso::firstOrNew(['empleado_id' => $empleado->id, 'anio_calendario' => $anioActual, 'mes' => $mes]);
                $registro->dias_tomados = ($registro->dias_tomados ?? 0) + $cantidad;
                $registro->save();
            }
            PeriodoVacacional::create([
                'empleado_id' => $empleado->id, 
                'anio_calendario' => $anioActual,
                'fecha_inicio' => $request->fecha_inicio, 
                'fecha_fin' => $request->fecha_fin,
                'fecha_regreso' => $fin->copy()->addDay()->toDateString(), 
                'dias' => $diasPeriodo, 
                'observaciones' => $request->input('observaciones'), // <-- ADICIONADO AQUÍ PARA GUARDAR EN LA BD
            ]);
        });
    } catch (\Exception $e) {
        return back()->withErrors(['error' => 'Ocurrió un error al guardar el registro.'])->withInput();
    }
    return back()->with('success', 'Registro de días de vacaciones actualizado correctamente.');
})->name('empleados.vacaciones.guardar');

Route::get('/empleados/{empleado}/vacaciones/pdf', function (Request $request, Empleado $empleado) {
    if (! session('logeado')) return redirect()->route('login');

    // Determine the year for the PDF. If a specific periodo_id is provided, use its fecha_inicio year
    $anioActual = Carbon::now()->year;
    $periodoId = $request->query('periodo_id');
    if ($periodoId) {
        $p = PeriodoVacacional::find($periodoId);
        if ($p && $p->fecha_inicio) {
            $anioActual = Carbon::parse($p->fecha_inicio)->year;
        }
    }

    $antiguedadAnios = (int) floor(Carbon::parse($empleado->fecha_ingreso)->diffInYears(Carbon::now()));
    $ley = LeyVacacion::where('anios_antiguedad', '<=', $antiguedadAnios)->orderBy('anios_antiguedad', 'desc')->first();
    $diasDerecho = $ley?->dias_derecho ?? 0;

    $registros = RegistroDescanso::where('empleado_id', $empleado->id)->where('anio_calendario', $anioActual)->orderBy('mes')->get();
    $diasTomados = $registros->sum('dias_tomados');
    $diasRestantes = max(0, $diasDerecho - $diasTomados);

    $periodosVacacionales = PeriodoVacacional::where('empleado_id', $empleado->id)
        ->whereNull('deleted_at')
        ->orderBy('fecha_inicio', 'desc')
        ->get();

    $periodoSeleccionado = null;
    if ($request->has('periodo_id')) {
        $periodoSeleccionado = PeriodoVacacional::where('empleado_id', $empleado->id)
            ->find($request->query('periodo_id'));
    }
    if (! $periodoSeleccionado) {
        $periodoSeleccionado = $periodosVacacionales->first();
    }

    $meses = [
        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio',
        7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
    ];

    $puesto = $empleado->puesto_id ? Puesto::find($empleado->puesto_id) : null;
    $fecha = Carbon::now();

    $html = view('empleados.pdf', compact('empleado', 'anioActual', 'antiguedadAnios', 'diasDerecho', 'diasTomados', 'diasRestantes', 'registros', 'periodosVacacionales', 'periodoSeleccionado', 'meses', 'puesto', 'fecha'))->render();

    $dompdf = new Dompdf;
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    return response($dompdf->output(), 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="vacaciones_'.str_replace(' ', '_', $empleado->nombre.'_'.$empleado->apellido_paterno.'_'.$empleado->apellido_materno).'_'.$anioActual.'.pdf"',
    ]);
})->name('empleados.vacaciones.pdf');

Route::get('/empleados/{empleado}/vacaciones/historial/pdf', function (Empleado $empleado) {
    if (! session('logeado')) return redirect()->route('login');

    $anioActual = Carbon::now()->year;
    $antiguedadAnios = (int) floor(Carbon::parse($empleado->fecha_ingreso)->diffInYears(Carbon::now()));
    $ley = LeyVacacion::where('anios_antiguedad', '<=', $antiguedadAnios)->orderBy('anios_antiguedad', 'desc')->first();
    $diasDerecho = $ley?->dias_derecho ?? 0;

    $registros = RegistroDescanso::where('empleado_id', $empleado->id)
        ->where('anio_calendario', $anioActual)
        ->orderBy('mes')
        ->get();
    $diasTomados = $registros->sum('dias_tomados');
    $diasRestantes = max(0, $diasDerecho - $diasTomados);

    $periodosVacacionales = PeriodoVacacional::where('empleado_id', $empleado->id)
        ->where('anio_calendario', $anioActual)
        ->whereNull('deleted_at')
        ->orderBy('fecha_inicio', 'asc')
        ->get();

    $totalDiasSolicitados = $periodosVacacionales->sum('dias');

    $meses = [
        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio',
        7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
    ];

    $puesto = $empleado->puesto_id ? Puesto::find($empleado->puesto_id) : null;
    $fecha = Carbon::now();

    $html = view('empleados.pdf_historial', compact(
        'empleado',
        'anioActual',
        'antiguedadAnios',
        'diasDerecho',
        'diasTomados',
        'diasRestantes',
        'registros',
        'periodosVacacionales',
        'totalDiasSolicitados',
        'meses',
        'puesto',
        'fecha'
    ))->render();

    $dompdf = new Dompdf;
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    return response($dompdf->output(), 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="historial_vacaciones_'.str_replace(' ', '_', $empleado->nombre.'_'.$empleado->apellido_paterno.'_'.$empleado->apellido_materno).'_'.$anioActual.'.pdf"',
    ]);
})->name('empleados.vacaciones.historial.pdf');

// Reporte global en PDF (Panel)
Route::get('/panel/reporte/pdf', function () {
    if (! session('logeado')) return redirect()->route('login');

    $anioActual = Carbon::now()->year;
    $empleados = Empleado::orderBy('nombre')->get();
    $rows = [];

    foreach ($empleados as $empleado) {
        $antiguedadAnios = (int) floor(Carbon::parse($empleado->fecha_ingreso)->diffInYears(Carbon::now()));
        $ley = LeyVacacion::where('anios_antiguedad', '<=', $antiguedadAnios)->orderBy('anios_antiguedad', 'desc')->first();
        $diasDerecho = $ley?->dias_derecho ?? 0;

        $registros = RegistroDescanso::where('empleado_id', $empleado->id)
            ->where('anio_calendario', $anioActual)
            ->whereNull('deleted_at')
            ->get();
        $diasTomados = $registros->sum('dias_tomados');
        $diasAdeuda = max(0, $diasDerecho - $diasTomados);

        $puesto = $empleado->puesto_id ? Puesto::find($empleado->puesto_id) : null;

        $rows[] = [
            'empleado' => $empleado->nombre . ' ' . $empleado->apellido_paterno . ' ' . $empleado->apellido_materno,
            'puesto' => $puesto?->nombre ?? null,
            'fecha_ingreso' => $empleado->fecha_ingreso,
            'dias_derecho' => $diasDerecho,
            'dias_tomados' => $diasTomados,
            'dias_adeuda' => $diasAdeuda,
        ];
    }

    $html = view('pdfs.empleados_todos', compact('rows', 'anioActual'))->render();

    $dompdf = new Dompdf;
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    return response($dompdf->output(), 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="reporte_vacaciones_'.$anioActual.'.pdf"',
    ]);
})->name('panel.reporte.pdf');


// | RUTAS AJAX PARA EL HISTORIAL DE VACACIONES (MODAL)

Route::get('/periodos/{id}', function ($id) {
    if (! session('logeado')) return response()->json(['error' => 'No autorizado'], 401);
    
    $periodo = PeriodoVacacional::with('empleado')->find($id);
    if (!$periodo) return response()->json(['error' => 'Periodo no encontrado'], 404);

    return response()->json([
        'id' => $periodo->id,
        'empleado_nombre' => $periodo->empleado ? $periodo->empleado->nombre . ' ' . $periodo->empleado->apellido_paterno : 'N/A',
        'fecha_inicio' => Carbon::parse($periodo->fecha_inicio)->format('Y-m-d'),
        'fecha_fin' => Carbon::parse($periodo->fecha_fin)->format('Y-m-d'),
        'dias' => $periodo->dias,
        'observaciones' => $periodo->observaciones ?? ''
    ]);
});
Route::get('/api/eventos-vacaciones', function () {
    return \App\Models\PeriodoVacacional::with('empleado')
        ->whereNull('deleted_at')
        ->get()
        ->map(function ($p) {
            return [
                'title' => $p->empleado->nombre . ' ' . substr($p->empleado->apellido_paterno, 0, 1) . '.',
                'start' => $p->fecha_inicio,
                'end'   => \Illuminate\Support\Carbon::parse($p->fecha_fin)->addDay()->toDateString(),
                'backgroundColor' => '#124416',
                'borderColor'     => '#124416',
                'textColor'       => '#ffffff',
                'classNames'      => ['evento-moderno'] // Clase para estilo extra
            ];
        });
});

Route::put('/periodos/{id}', function (Request $request, $id) {
    if (! session('logeado')) return response()->json(['error' => 'No autorizado'], 401);

    \Log::info('PUT /periodos payload', ['id' => $id, 'payload' => $request->all()]);

    $validator = Validator::make($request->all(), [
        'multiple_dates' => 'required|string', // Requerimos el string de días salteados/juntos del calendario
        'fecha_inicio'   => 'required|date',
        'fecha_fin'      => 'required|date|after_or_equal:fecha_inicio',
        'observaciones'  => 'nullable|string|max:1000',
    ]);

    if ($validator->fails()) {
        \Log::warning('PUT /periodos validation failed', ['errors' => $validator->errors()->all()]);
        return response()->json(['error' => 'Validación de datos fallida.'], 422);
    }

    try {
        DB::transaction(function () use ($request, $id) {
            $periodo = PeriodoVacacional::findOrFail($id);
            $empleadoId = $periodo->empleado_id;
            $anioActual = $periodo->anio_calendario;

            // 1. Obtener y limpiar los días seleccionados uno a uno en el calendario
            $selectedDates = array_filter(array_map('trim', explode(',', $request->input('multiple_dates'))));
            if (empty($selectedDates)) {
                throw new \Exception("Debe seleccionar al menos un día en el calendario.");
            }
            sort($selectedDates);

            $inicioNuevo = Carbon::parse($selectedDates[0]);
            $finNuevo = Carbon::parse(end($selectedDates));

            if ($inicioNuevo->year !== $anioActual || $finNuevo->year !== $anioActual) {
                throw new \Exception("Las fechas editadas deben estar dentro del año {$anioActual}.");
            }

            // El total de días nuevos es el conteo exacto de los días seleccionados en el picker
            $diasNuevos = count($selectedDates);

            // 2. Validaciones de límite de días disponibles
            $empleado = Empleado::find($empleadoId);
            $antiguedadAnios = (int) floor(Carbon::parse($empleado->fecha_ingreso)->diffInYears(Carbon::now()));
            $ley = LeyVacacion::where('anios_antiguedad', '<=', $antiguedadAnios)->orderBy('anios_antiguedad', 'desc')->first();
            $diasDerecho = $ley ? $ley->dias_derecho : 0;

            $diasTomadosTotales = RegistroDescanso::where('empleado_id', $empleadoId)
                ->where('anio_calendario', $anioActual)
                ->sum('dias_tomados');
            
            $diasTomadosSinEstePeriodo = $diasTomadosTotales - $periodo->dias;

            if (($diasTomadosSinEstePeriodo + $diasNuevos) > $diasDerecho) {
                $disponibles = $diasDerecho - $diasTomadosSinEstePeriodo;
                throw new \Exception("El empleado solo tiene {$disponibles} día(s) disponible(s). No puedes asignarle {$diasNuevos} días.");
            }

            // 3. REVERTIR DÍAS VIEJOS EN EL REGISTRO MENSUAL
            // Usamos la lógica de mapeo original del periodo anterior para restar correctamente los acumulados por mes
            $inicioViejo = Carbon::parse($periodo->fecha_inicio);
            $finViejo = Carbon::parse($periodo->fecha_fin);
            
            $diasPorMesViejos = [];
            $diasARevertir = $periodo->dias;
            // Nota: Si tu antigua inserción guardaba días intermedios de corrido, esto los limpiará.
            for ($fecha = $inicioViejo->copy(); $fecha->lte($finViejo); $fecha->addDay()) {
                if ($diasARevertir <= 0) break;
                if (!$fecha->isWeekend()) {
                    $mes = $fecha->month;
                    $diasPorMesViejos[$mes] = ($diasPorMesViejos[$mes] ?? 0) + 1;
                    $diasARevertir--;
                }
            }

            foreach ($diasPorMesViejos as $mes => $cantidad) {
                $registro = RegistroDescanso::where('empleado_id', $empleadoId)
                    ->where('anio_calendario', $anioActual)->where('mes', $mes)->first();
                if ($registro) {
                    $registro->dias_tomados = max(0, $registro->dias_tomados - $cantidad);
                    $registro->save();
                }
            }

            // 4. ASIGNAR NUEVOS DÍAS POR MES (Basado en la selección real día a día)
            $diasPorMesNuevos = [];
            foreach ($selectedDates as $selectedDate) {
                $fechaObj = Carbon::parse($selectedDate);
                $mesN = $fechaObj->month;
                $diasPorMesNuevos[$mesN] = ($diasPorMesNuevos[$mesN] ?? 0) + 1;
            }

            foreach ($diasPorMesNuevos as $mes => $cantidad) {
                $registro = RegistroDescanso::firstOrNew(['empleado_id' => $empleadoId, 'anio_calendario' => $anioActual, 'mes' => $mes]);
                $registro->dias_tomados = ($registro->dias_tomados ?? 0) + $cantidad;
                $registro->save();
            }

            // 5. ACTUALIZAR EL PERIODO VACACIONAL
            $periodo->fecha_inicio = $request->fecha_inicio; // Mantiene el valor visual del formulario
            $periodo->fecha_fin = $request->fecha_fin;     // Mantiene el valor visual del formulario
            $periodo->dias = $diasNuevos;                  // Guarda los días reales seleccionados
            
            // Corrección de observaciones: Si viene vacío en el request, guardamos un string vacío o mantenemos el valor limpio sin romperlo
            $periodo->observaciones = $request->has('observaciones') ? $request->input('observaciones') : $periodo->observaciones;
            
            $periodo->fecha_regreso = $finNuevo->copy()->addDay()->toDateString();
            $periodo->save(); 

            \Log::info('Periodo actualizado', ['id' => $periodo->id, 'observaciones' => $periodo->observaciones, 'dias' => $periodo->dias]);
        });

        return response()->json(['success' => true, 'mensaje' => 'Período actualizado correctamente']);
    } catch (\Exception $e) {
        \Log::error('PUT /periodos error', ['message' => $e->getMessage()]);
        return response()->json(['error' => $e->getMessage()], 500); 
    }
});

Route::delete('/periodos/{id}', function ($id) {
    if (! session('logeado')) return response()->json(['error' => 'No autorizado'], 401);
    
    try {
        DB::transaction(function () use ($id) {
            $periodo = PeriodoVacacional::findOrFail($id);
            $empleadoId = $periodo->empleado_id;
            $anioActual = $periodo->anio_calendario;
            
            $inicio = Carbon::parse($periodo->fecha_inicio);
            $fin = Carbon::parse($periodo->fecha_fin);
            
            $diasPorMes = [];
            $mesInicio = $inicio->month;
            $mesFin = $fin->month;

            if ($mesInicio === $mesFin) {
                $diasPorMes[$mesInicio] = $periodo->dias;
            } else {
                $asignarPrimerMes = min($periodo->dias, clone $inicio->endOfMonth()->diffInDays($inicio) + 1);
                $diasPorMes[$mesInicio] = $asignarPrimerMes;
                $sobrante = $periodo->dias - $asignarPrimerMes;
                if ($sobrante > 0) { $diasPorMes[$mesFin] = $sobrante; }
            }

            foreach ($diasPorMes as $mes => $cantidad) {
                $registro = RegistroDescanso::where('empleado_id', $empleadoId)
                    ->where('anio_calendario', $anioActual)->where('mes', $mes)->first();
                if ($registro) {
                    $registro->dias_tomados = max(0, $registro->dias_tomados - $cantidad);
                    $registro->save();
                }
            }
            $periodo->delete();
        });

        return response()->json(['success' => true, 'mensaje' => 'Período eliminado correctamente']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
    }
});