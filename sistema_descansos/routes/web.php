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
use App\Models\DiaEspecial;

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

// CORRECCIÓN: Se eliminaron las rutas duplicadas de puestos y se dejaron únicamente estas dos funcionales
Route::post('/puestos', [PuestoController::class, 'store'])->name('puestos.store');
Route::delete('/puestos/{id}', [PuestoController::class, 'destroy'])->name('puestos.destroy');

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
// CORRECCIÓN SEGURO: Cambiado a parámetro {id} explícito para que coincida con tu controlador
Route::put('/perfiles/{id}', [UsuariosController::class, 'update'])->name('perfiles.update');
Route::delete('/perfiles/{id}', [UsuariosController::class, 'destroy'])->name('perfiles.destroy');
Route::post('/perfil/update', [ProfileController::class, 'update'])->name('perfil.update');
Route::post('/perfil/password', [ProfileController::class, 'changePassword'])->name('perfil.password');

Route::get('/dias-especiales', function () {
    if (! session('logeado')) return redirect()->route('login');

    $diasEspeciales = DiaEspecial::orderBy('fecha_inicio', 'desc')->get();
    $empleados = Empleado::orderBy('nombre')->get();
    return view('dias_especiales.index', compact('diasEspeciales','empleados'));
})->name('dias-especiales.index');

Route::post('/dias-especiales', function (Request $request) {
    if (! session('logeado')) return redirect()->route('login');

    $request->validate([
        'tipo' => 'required|in:descanso,festivo,institucional',
        'titulo' => 'required|string|max:255',
        'fecha_inicio' => 'required|date',
        'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        'selection_mode' => 'required|string|in:personalizado,semana,mes,varios',
        'multiple_dates' => 'required|string',
        'aplica_todos' => 'nullable|boolean',
        'empleados' => 'nullable|array',
        'empleados.*' => 'integer|exists:empleados,id',
        'observaciones' => 'nullable|string|max:1000',
    ]);

    $selectedDates = array_filter(array_map('trim', explode(',', $request->input('multiple_dates'))));
    $count = count($selectedDates);

    // Calcular fecha_inicio y fecha_fin desde selectedDates
    if (empty($selectedDates)) {
        return back()->withErrors(['multiple_dates' => 'Debes seleccionar al menos una fecha.'])->withInput();
    }
    sort($selectedDates);
    $fechaInicio = $selectedDates[0];
    $fechaFin = $selectedDates[count($selectedDates) - 1];

    // Validaciones según modo de selección
    $mode = $request->input('selection_mode');
    if ($mode === 'semana' && $count !== 7) {
        return back()->withErrors(['multiple_dates' => 'Para selección semanal debes elegir exactamente 7 días.'])->withInput();
    }
    if ($mode === 'mes') {
        $start = Carbon::parse($fechaInicio);
        $daysInMonth = $start->daysInMonth;
        if ($count !== $daysInMonth) {
            return back()->withErrors(['multiple_dates' => "Para selección por mes debes seleccionar los {$daysInMonth} días del mes."])->withInput();
        }
    }

    // Validación: si estás creando un 'descanso' no permitir seleccionar fechas que ya sean festivas o institucionales
    if ($request->input('tipo') === 'descanso') {
        $conflicts = [];
        foreach ($selectedDates as $sd) {
            $d = Carbon::parse($sd)->toDateString();
            $exists = DiaEspecial::whereIn('tipo', ['festivo', 'institucional'])
                ->whereDate('fecha_inicio', '<=', $d)
                ->whereDate('fecha_fin', '>=', $d)
                ->exists();
            if ($exists) $conflicts[] = $d;
        }
        if (!empty($conflicts)) {
            return back()->withErrors(['multiple_dates' => 'Las siguientes fechas no pueden asignarse como descanso porque son festivas o institucionales: ' . implode(', ', $conflicts)])->withInput();
        }
    }

    $data = [
        'tipo' => $request->input('tipo'),
        'titulo' => $request->input('titulo'),
        'fecha_inicio' => $fechaInicio,
        'fecha_fin' => $fechaFin,
        'observaciones' => $request->input('observaciones'),
    ];
    if ($request->input('aplica_todos')) {
        $data['aplicado_a'] = ['all'];
    } else {
        $data['aplicado_a'] = $request->input('empleados') ?: null;
    }

    DiaEspecial::create($data);

    return redirect()->route('dias-especiales.index');
})->name('dias-especiales.store');

Route::delete('/dias-especiales/{id}', function ($id) {
    if (! session('logeado')) return redirect()->route('login');

    DiaEspecial::findOrFail($id)->delete();
    return redirect()->route('dias-especiales.index');
})->name('dias-especiales.destroy');


// | RUTAS DE LÓGICA DE VACACIONES (MÓDULO INDIVIDUAL)

Route::get('/empleados/{empleado}/vacaciones', function (Empleado $empleado) {
    if (! session('logeado')) return redirect()->route('login');

    $anioActual = Carbon::now()->year;
    $antiguedadAnios = (int) floor(Carbon::parse($empleado->fecha_ingreso)->diffInYears(Carbon::now()));
    $ley = LeyVacacion::where('anios_antiguedad', '<=', $antiguedadAnios)->orderBy('anios_antiguedad', 'desc')->first();
    $diasExtra = DB::table('ajustes_dias_vacaciones')->where('empleado_id', $empleado->id)->where('anio', '<=', $anioActual)->sum('dias');
    $diasDerecho = ($ley?->dias_derecho ?? 0) + $diasExtra;

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

    $puestoNombre = $empleado->puesto_id ? Puesto::find($empleado->puesto_id)?->nombre : 'No asignado';

    return view('empleados.vacaciones', compact('empleado', 'anioActual', 'antiguedadAnios', 'diasDerecho', 'diasTomados', 'diasRestantes', 'meses', 'registroPorMes', 'puestoNombre'));
})->name('empleados.vacaciones');

Route::post('/empleados/{empleado}/vacaciones', function (Request $request, Empleado $empleado) {
    if (! session('logeado')) return redirect()->route('login');

    $anioActual = Carbon::now()->year;
    $antiguedadAnios = (int) floor(Carbon::parse($empleado->fecha_ingreso)->diffInYears(Carbon::now()));
    $ley = LeyVacacion::where('anios_antiguedad', '<=', $antiguedadAnios)->orderBy('anios_antiguedad', 'desc')->first();
    $diasExtra = DB::table('ajustes_dias_vacaciones')->where('empleado_id', $empleado->id)->where('anio', '<=', $anioActual)->sum('dias');
    $diasDerecho = ($ley?->dias_derecho ?? 0) + $diasExtra;

    $validator = Validator::make($request->all(), [
        'multiple_dates'   => 'required|string',
        'fecha_inicio'     => 'required|date',
        'fecha_fin'        => 'required|date|after_or_equal:fecha_inicio',
        'dias_solicitados' => 'required|integer|min:1',
        'observaciones'    => 'nullable|string|max:1000',
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
        DB::transaction(function () use ($diasPorMes, $empleado, $anioActual, $inicio, $fin, $request, $diasPeriodo, $selectedDates) {
            foreach ($diasPorMes as $mes => $cantidad) {
                $registro = RegistroDescanso::firstOrNew(['empleado_id' => $empleado->id, 'anio_calendario' => $anioActual, 'mes' => $mes]);
                $registro->dias_tomados = ($registro->dias_tomados ?? 0) + $cantidad;
                $registro->save();
            }

            PeriodoVacacional::create([
                'empleado_id' => $empleado->id,
                'anio_calendario' => $anioActual,
                'fecha_inicio' => $inicio->toDateString(),
                'fecha_fin' => $fin->toDateString(),
                'fecha_regreso' => $fin->copy()->addDay()->toDateString(),
                'dias' => $diasPeriodo,
                'observaciones' => $request->input('observaciones'),
                'multiple_dates' => $selectedDates,
            ]);
        });
    } catch (\Exception $e) {
        return back()->withErrors(['error' => 'Ocurrió un error al guardar el registro.'])->withInput();
    }
    return back()->with('success', 'Registro de días de vacaciones actualizado correctamente.');
})->name('empleados.vacaciones.guardar');

Route::get('/empleados/{empleado}/vacaciones/pdf', function (Request $request, Empleado $empleado) {
    if (! session('logeado')) return redirect()->route('login');

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
    $ajustesPorAnio = DB::table('ajustes_dias_vacaciones')
        ->where('empleado_id', $empleado->id)
        ->where('anio', '<=', $anioActual)
        ->orderBy('anio')
        ->get();
    $diasExtra = $ajustesPorAnio->sum('dias');
    $diasDerecho = ($ley?->dias_derecho ?? 0) + $diasExtra;

    $registros = RegistroDescanso::where('empleado_id', $empleado->id)->where('anio_calendario', $anioActual)->orderBy('mes')->get();
    $diasTomados = $registros->sum('dias_tomados');
    $diasRestantes = max(0, $diasDerecho - $diasTomados);

    // Allocate taken days to ajustes (oldest-first) so we can display which year provided the days
    $remainingTaken = $diasTomados;
    $ajustesUsados = collect();
    foreach ($ajustesPorAnio as $aj) {
        $used = min($aj->dias, $remainingTaken);
        $ajustesUsados->push((object)[
            'anio' => $aj->anio,
            'dias' => $aj->dias,
            'usado' => $used,
            'restante' => max(0, $aj->dias - $used),
            'motivo' => $aj->motivo ?? null,
        ]);
        $remainingTaken -= $used;
        if ($remainingTaken <= 0) {
            $remainingTaken = 0;
        }
    }
    // If still taken days remain, attribute them to the base derecho (current year entitlement)
    $usadoBase = 0;
    if ($remainingTaken > 0) {
        $usadoBase = min(($ley?->dias_derecho ?? 0), $remainingTaken);
        $remainingTaken -= $usadoBase;
    }

    $periodosVacacionales = PeriodoVacacional::where('empleado_id', $empleado->id)
        ->where('anio_calendario', $anioActual)
        ->whereNull('deleted_at')
        ->orderBy('anio_calendario', 'asc')
        ->orderBy('fecha_inicio', 'asc')
        ->get();

    $periodoSeleccionado = null;
    if ($request->has('periodo_id')) {
        $periodoSeleccionado = PeriodoVacacional::where('empleado_id', $empleado->id)
            ->whereNull('deleted_at')
            ->find($request->query('periodo_id'));
    }
    if (! $periodoSeleccionado && $periodosVacacionales->count() > 0) {
        $periodoSeleccionado = $periodosVacacionales->first();
    }

    $periodoAnio = $periodoSeleccionado?->anio_calendario ?? $anioActual;
    $periodoVisual = $periodoAnio;
    $periodoResidual = $ajustesUsados->firstWhere('restante', '>', 0);
    if ($periodoResidual) {
        $periodoVisual = $periodoResidual->anio;
    }

    $meses = [
        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio',
        7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
    ];

    $puesto = $empleado->puesto_id ? Puesto::find($empleado->puesto_id) : null;
    $fecha = Carbon::now();

    $html = view('empleados.pdf', compact('empleado', 'anioActual', 'antiguedadAnios', 'diasDerecho', 'diasTomados', 'diasRestantes', 'registros', 'periodosVacacionales', 'periodoSeleccionado', 'periodoAnio', 'periodoVisual', 'meses', 'puesto', 'fecha', 'ajustesPorAnio', 'ajustesUsados', 'usadoBase'))->render();

    $dompdf = new Dompdf;
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    return response($dompdf->output(), 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="vacaciones_'.str_replace(' ', '_', $empleado->nombre.'_'.$empleado->apellido_paterno.'_'.$empleado->apellido_materno).'_'.($periodoAnio ?? $anioActual).'.pdf"',
    ]);
})->name('empleados.vacaciones.pdf');

Route::get('/empleados/{empleado}/vacaciones/historial/pdf', function (Empleado $empleado) {
    if (! session('logeado')) return redirect()->route('login');

    $anioActual = Carbon::now()->year;
    $antiguedadAnios = (int) floor(Carbon::parse($empleado->fecha_ingreso)->diffInYears(Carbon::now()));
    $ley = LeyVacacion::where('anios_antiguedad', '<=', $antiguedadAnios)->orderBy('anios_antiguedad', 'desc')->first();
    $diasExtra = DB::table('ajustes_dias_vacaciones')->where('empleado_id', $empleado->id)->where('anio', '<=', $anioActual)->sum('dias');
    $diasDerecho = ($ley?->dias_derecho ?? 0) + $diasExtra;

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
        $diasExtra = DB::table('ajustes_dias_vacaciones')->where('empleado_id', $empleado->id)->where('anio', '<=', $anioActual)->sum('dias');
        $diasDerecho = ($ley?->dias_derecho ?? 0) + $diasExtra;

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
        'fecha_inicio' => $periodo->fecha_inicio ? Carbon::parse($periodo->fecha_inicio)->format('Y-m-d') : null,
        'fecha_fin' => $periodo->fecha_fin ? Carbon::parse($periodo->fecha_fin)->format('Y-m-d') : null,
        'dias' => $periodo->dias,
        'multiple_dates' => $periodo->multiple_dates ?? [],
        'observaciones' => $periodo->observaciones ?? ''
    ]);
});

// CORRECCIÓN EXACTA DEL ERROR DEL CALENDARIO:
Route::get('/api/eventos-vacaciones', function () {
    $vacaciones = PeriodoVacacional::with('empleado')
        ->whereNull('deleted_at')
        ->whereHas('empleado', function($q) {
            $q->whereNull('deleted_at');
        })
        ->get()
        ->flatMap(function ($p) {
            // Paleta sin azules ni rojos (tonos verdes, morados, naranjas, marrones, teal)
            $palette = [
                '#124416', 
                '#ee7a9d', 
                '#059669', 
                '#b91081', 
                '#0b4e4e', 
                '#7c3aed', 
                '#671e69', 
                '#a87e3b', 
                '#92400e', 
                '#d97706', 
                '#fcda7f', 
                '#4c1d95',
            ];

            // Determinista por empleado id (si no existe, por texto del título)
            $key = $p->empleado_id ?: ($p->empleado ? crc32($p->empleado->nombre . $p->empleado->apellido_paterno) : rand());
            $color = $palette[$key % count($palette)];

            $title = $p->empleado ? $p->empleado->nombre . ' ' . substr($p->empleado->apellido_paterno, 0, 1) . '.' : 'Vacaciones Institucionales';
            $baseEvent = [
                'title' => $title,
                'backgroundColor' => $color,
                'borderColor'     => $color,
                'textColor'       => '#ffffff',
                'classNames'      => ['evento-moderno', 'evento-vacacion-general'],
                'extendedProps'   => ['tipo' => 'periodo_vacacional', 'is_special' => false],
            ];

            if (!empty($p->multiple_dates) && is_array($p->multiple_dates)) {
                return collect($p->multiple_dates)->map(function ($date) use ($baseEvent) {
                    return array_merge($baseEvent, [
                        'start' => $date,
                        'end'   => \Illuminate\Support\Carbon::parse($date)->addDay()->toDateString(),
                    ]);
                });
            }

            if ($p->fecha_inicio && $p->fecha_fin) {
                return [[
                    'start' => \Illuminate\Support\Carbon::parse($p->fecha_inicio)->toDateString(),
                    'end'   => \Illuminate\Support\Carbon::parse($p->fecha_fin)->addDay()->toDateString(),
                ] + $baseEvent];
            }

            return [];
        })->filter(function($e) { return !empty($e['start']) && !empty($e['end']); })->toBase();

    $especiales = DiaEspecial::orderBy('fecha_inicio', 'desc')->get()->map(function ($dia) {
        return [
            'title' => $dia->titulo,
            'start' => \Illuminate\Support\Carbon::parse($dia->fecha_inicio)->toDateString(),
            'end'   => \Illuminate\Support\Carbon::parse($dia->fecha_fin)->addDay()->toDateString(),
            'backgroundColor' => $dia->color,
            'borderColor'     => $dia->color,
            'textColor'       => $dia->text_color,
            'display' => 'auto',
            'classNames'      => ['evento-especial', 'evento-' . $dia->tipo],
            'extendedProps'   => ['tipo' => $dia->tipo, 'is_special' => true, 'aplicado_a' => $dia->aplicado_a],
        ];
    })->toBase();

    $events = $vacaciones->merge($especiales)->values();
    return response()->json($events);
});

Route::put('/periodos/{id}', function (Request $request, $id) {
    if (! session('logeado')) return response()->json(['error' => 'No autorizado'], 401);

    \Log::info('PUT /periodos payload', ['id' => $id, 'payload' => $request->all()]);

    $validator = Validator::make($request->all(), [
        'multiple_dates' => 'nullable|string', 
        'fecha_inicio'   => 'required|date',
        'fecha_fin'      => 'required|date|after_or_equal:fecha_inicio',
        'observaciones'  => 'nullable|string|max:1000',
    ]);

    if ($validator->fails()) {
        \Log::warning('PUT /periodos validation failed', ['errors' => $validator->errors()->all()]);
        return response()->json([
            'error' => 'Validación de datos fallida.',
            'errors' => $validator->errors()->messages()
        ], 422);
    }

    try {
        return DB::transaction(function () use ($request, $id) {
            $periodo = PeriodoVacacional::findOrFail($id);
            $empleadoId = $periodo->empleado_id;
            $anioActual = $periodo->anio_calendario;

            $inicioReq = Carbon::parse($request->input('fecha_inicio'));
            $finReq = Carbon::parse($request->input('fecha_fin'));
            $selectedDates = [];

            // Si viene 'multiple_dates' estructurado, lo usamos; si no, intentamos usar el arreglo guardado o el rango completo.
            if ($request->filled('multiple_dates') && trim($request->input('multiple_dates')) !== '') {
                $selectedDates = array_filter(array_map('trim', explode(',', $request->input('multiple_dates'))));
            } elseif (!empty($periodo->multiple_dates)) {
                $selectedDates = array_filter($periodo->multiple_dates);
            } else {
                for ($d = $inicioReq->copy(); $d->lte($finReq); $d->addDay()) {
                    $selectedDates[] = $d->toDateString();
                }
            }

            if (empty($selectedDates)) {
                return response()->json(['error' => 'Debe seleccionar al menos un día válido.'], 422);
            }

            sort($selectedDates);
            $inicioNuevo = Carbon::parse($selectedDates[0]);
            $finNuevo = Carbon::parse(end($selectedDates));

            if ($inicioNuevo->year !== $anioActual || $finNuevo->year !== $anioActual) {
                return response()->json(['error' => "Las fechas editadas deben estar dentro del año {$anioActual}."], 422);
            }

            $diasNuevos = count($selectedDates);
            if ($diasNuevos === 0) {
                return response()->json(['error' => 'Debe seleccionar al menos un día válido.'], 422);
            }

            $empleado = Empleado::find($empleadoId);
            $antiguedadAnios = (int) floor(Carbon::parse($empleado->fecha_ingreso)->diffInYears(Carbon::now()));
            $ley = LeyVacacion::where('anios_antiguedad', '<=', $antiguedadAnios)->orderBy('anios_antiguedad', 'desc')->first();
            $diasExtra = DB::table('ajustes_dias_vacaciones')->where('empleado_id', $empleadoId)->where('anio', '<=', $anioActual)->sum('dias');
            $diasDerecho = ($ley ? $ley->dias_derecho : 0) + $diasExtra;

            $diasTomadosTotales = RegistroDescanso::where('empleado_id', $empleadoId)
                ->where('anio_calendario', $anioActual)
                ->sum('dias_tomados');
            $diasTomadosSinEstePeriodo = $diasTomadosTotales - $periodo->dias;

            if (($diasTomadosSinEstePeriodo + $diasNuevos) > $diasDerecho) {
                $disponibles = $diasDerecho - $diasTomadosSinEstePeriodo;
                return response()->json(['error' => "El empleado solo tiene {$disponibles} día(s) disponible(s). No puedes asignarle {$diasNuevos} días."], 422);
            }

            // Revertir los días viejos del RegistroDescanso usando los días reales guardados.
            $diasPorMesViejos = [];
            $diasViejos = [];
            if (!empty($periodo->multiple_dates)) {
                $diasViejos = $periodo->multiple_dates;
            } else {
                $inicioViejo = Carbon::parse($periodo->fecha_inicio);
                $finViejo = Carbon::parse($periodo->fecha_fin);
                for ($fecha = $inicioViejo->copy(); $fecha->lte($finViejo); $fecha->addDay()) {
                    $diasViejos[] = $fecha->toDateString();
                }
            }

            foreach ($diasViejos as $fechaVieja) {
                $fecha = Carbon::parse($fechaVieja);
                $mes = $fecha->month;
                $diasPorMesViejos[$mes] = ($diasPorMesViejos[$mes] ?? 0) + 1;
            }

            foreach ($diasPorMesViejos as $mes => $cantidad) {
                $registro = RegistroDescanso::where('empleado_id', $empleadoId)
                    ->where('anio_calendario', $anioActual)->where('mes', $mes)->first();
                if ($registro) {
                    $registro->dias_tomados = max(0, $registro->dias_tomados - $cantidad);
                    $registro->save();
                }
            }

            // Asignar los días nuevos al RegistroDescanso mensual
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

            $periodo->fecha_inicio = $inicioNuevo->toDateString();
            $periodo->fecha_fin = $finNuevo->toDateString();
            $periodo->dias = $diasNuevos;
            $periodo->multiple_dates = $selectedDates;
            $periodo->observaciones = $request->has('observaciones') ? $request->input('observaciones') : $periodo->observaciones;
            $periodo->fecha_regreso = $finNuevo->copy()->addDay()->toDateString();
            $periodo->save();

            \Log::info('Periodo actualizado con éxito', ['id' => $periodo->id]);

            return response()->json(['success' => true, 'mensaje' => 'Período actualizado correctamente']);
        });
    } catch (\Exception $e) {
        \Log::error('PUT /periodos error catastrófico', ['message' => $e->getMessage()]);
        return response()->json(['error' => 'Ocurrió un error en el servidor al procesar la actualización.'], 500); 
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