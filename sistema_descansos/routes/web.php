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

if (! function_exists('sumarDiasAsignadosPeriodos')) {
    function sumarDiasAsignadosPeriodos(int $empleadoId): int
    {
        return (int) DB::table('periodos')
            ->where('empleado_id', $empleadoId)
            ->whereNull('deleted_at')
            ->sum('dias_asignados');
    }
}

if (! function_exists('sumarDiasDisponiblesPeriodos')) {
    function sumarDiasDisponiblesPeriodos(int $empleadoId): int
    {
        return (int) DB::table('periodos')
            ->where('empleado_id', $empleadoId)
            ->whereNull('deleted_at')
            ->sum('dias_disponibles');
    }
}

if (! function_exists('obtenerResumenPeriodos')) {
    function obtenerResumenPeriodos(int $empleadoId)
    {
        return DB::table('periodos')
            ->where('empleado_id', $empleadoId)
            ->whereNull('deleted_at')
            ->orderBy('anio', 'asc')
            ->orderBy('id', 'asc')
            ->get()
            ->map(function ($periodo) {
                return (object) [
                    'id' => $periodo->id,
                    'anio' => (int) $periodo->anio,
                    'dias' => (int) $periodo->dias_asignados,
                    'usado' => max(0, (int) $periodo->dias_asignados - (int) $periodo->dias_disponibles),
                    'restante' => (int) $periodo->dias_disponibles,
                    'motivo' => null,
                ];
            });
    }
}

if (! function_exists('consumirDiasDePeriodos')) {
    function consumirDiasDePeriodos(int $empleadoId, array $selectedDates): array
    {
        if (empty($selectedDates)) {
            return [];
        }

        // Ordenamos las fechas cronológicamente
        sort($selectedDates);
        
        $desglose = [];
        $fechasRestantes = $selectedDates;

        $periodos = DB::table('periodos')
            ->where('empleado_id', $empleadoId)
            ->whereNull('deleted_at')
            ->where('dias_disponibles', '>', 0)
            ->orderBy('anio', 'asc')
            ->orderBy('id', 'asc')
            ->lockForUpdate()
            ->get();

        foreach ($periodos as $periodo) {
            if (empty($fechasRestantes)) {
                break;
            }

            $disponibles = (int) $periodo->dias_disponibles;
            if ($disponibles <= 0) {
                continue;
            }

            // Extraemos el tramo de fechas que este período puede cubrir (FIFO)
            $fechasParaEstePeriodo = array_splice($fechasRestantes, 0, $disponibles);
            $tomar = count($fechasParaEstePeriodo);

            if ($tomar > 0) {
                // Actualizamos la base de datos
                DB::table('periodos')
                    ->where('id', $periodo->id)
                    ->update([
                        'dias_disponibles' => $disponibles - $tomar,
                        'updated_at' => now(),
                    ]);

                // Calculamos los límites cronológicos del tramo
                $inicioTramo = \Illuminate\Support\Carbon::parse($fechasParaEstePeriodo[0]);
                $finTramo = \Illuminate\Support\Carbon::parse(end($fechasParaEstePeriodo));
                $regresoTramo = $finTramo->copy()->addDay();

                // Añadimos al desglose con las fechas específicas
                $desglose[] = [
                    'periodo_id'   => (int) $periodo->id,
                    'anio'         => (int) $periodo->anio,
                    'dias_tomados' => $tomar,
                    'fecha_inicio' => $inicioTramo->toDateString(),
                    'fecha_fin'    => $finTramo->toDateString(),
                    'fecha_regreso' => $regresoTramo->toDateString(),
                ];
            }
        }

        // Si quedaron fechas sin cubrir, lanzamos excepción
        if (! empty($fechasRestantes)) {
            throw new \RuntimeException('No hay días disponibles suficientes en los periodos para cubrir todas las fechas seleccionadas.');
        }

        return $desglose;
    }
}


if (! function_exists('reintegrarDiasAPeriodos')) {
    function reintegrarDiasAPeriodos(int $empleadoId, int $diasAReintegrar, bool $estricto = true): void
    {
        if ($diasAReintegrar <= 0) {
            return;
        }

        $restante = $diasAReintegrar;
        $periodos = DB::table('periodos')
            ->where('empleado_id', $empleadoId)
            ->whereNull('deleted_at')
            ->orderBy('anio', 'desc')
            ->orderBy('id', 'desc')
            ->lockForUpdate()
            ->get();

        foreach ($periodos as $periodo) {
            if ($restante <= 0) {
                break;
            }

            $usados = max(0, (int) $periodo->dias_asignados - (int) $periodo->dias_disponibles);
            if ($usados <= 0) {
                continue;
            }

            $devolver = min($restante, $usados);
            DB::table('periodos')
                ->where('id', $periodo->id)
                ->update([
                    'dias_disponibles' => ((int) $periodo->dias_disponibles) + $devolver,
                    'updated_at' => now(),
                ]);

            $restante -= $devolver;
        }

        if ($restante > 0 && $estricto) {
            throw new \RuntimeException('No fue posible reintegrar todos los días a los periodos.');
        }

        if ($restante > 0 && ! $estricto) {
            \Log::warning('Reintegro parcial en periodos', [
                'empleado_id' => $empleadoId,
                'dias_solicitados_reintegrar' => $diasAReintegrar,
                'dias_no_reintegrados' => $restante,
            ]);
        }
    }
}

if (! function_exists('asignarDiasAFifoPorPeriodos')) {
    function asignarDiasAFifoPorPeriodos($periodosBase, int $diasObjetivo): array
    {
        if ($diasObjetivo <= 0) {
            return [];
        }

        $restante = $diasObjetivo;
        $asignacionPorAnio = [];

        foreach ($periodosBase as $periodo) {
            if ($restante <= 0) {
                break;
            }

            $capacidad = max(0, (int) ($periodo->dias_asignados ?? 0));
            if ($capacidad <= 0) {
                continue;
            }

            $tomar = min($restante, $capacidad);
            $anio = (int) ($periodo->anio ?? 0);

            if (! isset($asignacionPorAnio[$anio])) {
                $asignacionPorAnio[$anio] = 0;
            }

            $asignacionPorAnio[$anio] += $tomar;
            $restante -= $tomar;
        }

        ksort($asignacionPorAnio);

        return $asignacionPorAnio;
    }
}

if (! function_exists('obtenerConsumoPorPeriodoDeSolicitud')) {
    function obtenerConsumoPorPeriodoDeSolicitud(int $empleadoId, ?int $periodoVacacionalId, bool $incluirDesfaseHistorico = true)
    {
        if (! $periodoVacacionalId) {
            return collect();
        }

        $periodosBase = DB::table('periodos')
            ->where('empleado_id', $empleadoId)
            ->whereNull('deleted_at')
            ->orderBy('anio', 'asc')
            ->orderBy('id', 'asc')
            ->get(['id', 'anio', 'dias_asignados', 'dias_disponibles']);

        if ($periodosBase->isEmpty()) {
            return collect();
        }

        // Importante: el consumo real sucede en el orden en que se guardan/actualizan los registros.
        // Para que el PDF del último registro refleje correctamente el periodo usado,
        // el cálculo debe seguir el orden de captura (id asc), no por fecha de disfrute.
        $solicitudes = PeriodoVacacional::query()
            ->where('empleado_id', $empleadoId)
            ->whereNull('deleted_at')
            ->orderBy('id', 'asc')
            ->get(['id', 'dias']);

        // Desfase histórico: días usados que existen en "periodos" pero que no están
        // representados por registros activos en periodos_vacacionales.
        // Esto permite que, si 2024 ya está agotado, el desglose salte a 2025.
        $usadoTotalActual = (int) $periodosBase->sum(function ($p) {
            return max(0, (int) ($p->dias_asignados ?? 0) - (int) ($p->dias_disponibles ?? 0));
        });
        $solicitadoTotalActivo = (int) $solicitudes->sum(function ($s) {
            return max(0, (int) ($s->dias ?? 0));
        });
        $desfaseInicial = $incluirDesfaseHistorico
            ? max(0, $usadoTotalActual - $solicitadoTotalActivo)
            : 0;

        $acumuladoAntes = 0;
        $diasSolicitud = null;

        foreach ($solicitudes as $solicitud) {
            if ((int) $solicitud->id === (int) $periodoVacacionalId) {
                $diasSolicitud = max(0, (int) $solicitud->dias);
                break;
            }

            $acumuladoAntes += max(0, (int) $solicitud->dias);
        }

        if ($diasSolicitud === null) {
            return collect();
        }

        $asignacionAntes = asignarDiasAFifoPorPeriodos($periodosBase, $desfaseInicial + $acumuladoAntes);
        $asignacionHasta = asignarDiasAFifoPorPeriodos($periodosBase, $desfaseInicial + $acumuladoAntes + $diasSolicitud);

        $anios = array_unique(array_merge(array_keys($asignacionAntes), array_keys($asignacionHasta)));
        sort($anios);

        $detalle = [];
        foreach ($anios as $anio) {
            $consumo = max(0, ((int) ($asignacionHasta[$anio] ?? 0)) - ((int) ($asignacionAntes[$anio] ?? 0)));
            if ($consumo > 0) {
                $detalle[] = (object) [
                    'anio' => (int) $anio,
                    'dias' => $consumo,
                ];
            }
        }

        return collect($detalle);
    }
}

if (! function_exists('reintegrarDiasPorConsumoDetallado')) {
    function reintegrarDiasPorConsumoDetallado(int $empleadoId, $consumoDetallado): void
    {
        $detalle = collect($consumoDetallado ?? []);
        if ($detalle->isEmpty()) {
            return;
        }

        foreach ($detalle as $item) {
            $anio = (int) ($item->anio ?? 0);
            $diasPendientes = max(0, (int) ($item->dias ?? 0));

            if ($anio <= 0 || $diasPendientes <= 0) {
                continue;
            }

            $periodosAnio = DB::table('periodos')
                ->where('empleado_id', $empleadoId)
                ->where('anio', $anio)
                ->whereNull('deleted_at')
                ->orderBy('id', 'asc')
                ->lockForUpdate()
                ->get(['id', 'dias_asignados', 'dias_disponibles']);

            foreach ($periodosAnio as $periodo) {
                if ($diasPendientes <= 0) {
                    break;
                }

                $espacio = max(0, (int) $periodo->dias_asignados - (int) $periodo->dias_disponibles);
                if ($espacio <= 0) {
                    continue;
                }

                $devolver = min($diasPendientes, $espacio);

                DB::table('periodos')
                    ->where('id', $periodo->id)
                    ->update([
                        'dias_disponibles' => ((int) $periodo->dias_disponibles) + $devolver,
                        'updated_at' => now(),
                    ]);

                $diasPendientes -= $devolver;
            }

            if ($diasPendientes > 0) {
                throw new \RuntimeException("No fue posible reintegrar {$diasPendientes} día(s) al periodo {$anio}.");
            }
        }
    }
}


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
    $diasDerecho = (int) DB::table('periodos')
        ->where('empleado_id', $empleado->id)
        ->whereNull('deleted_at')
        ->sum('dias_asignados');
    $diasRestantes = (int) DB::table('periodos')
        ->where('empleado_id', $empleado->id)
        ->whereNull('deleted_at')
        ->sum('dias_disponibles');

    $periodoMasAntiguoConSaldo = DB::table('periodos')
        ->where('empleado_id', $empleado->id)
        ->whereNull('deleted_at')
        ->where('dias_disponibles', '>', 0)
        ->orderBy('anio', 'asc')
        ->orderBy('id', 'asc')
        ->first();
    $diasExtra = $periodoMasAntiguoConSaldo ? (int) $periodoMasAntiguoConSaldo->dias_disponibles : 0;
    $anioPeriodoExtra = $periodoMasAntiguoConSaldo ? (int) $periodoMasAntiguoConSaldo->anio : null;
    $periodosDisponibles = DB::table('periodos')
        ->where('empleado_id', $empleado->id)
        ->whereNull('deleted_at')
        ->where('dias_disponibles', '>', 0)
        ->orderBy('anio', 'asc')
        ->orderBy('id', 'asc')
        ->get(['id', 'anio', 'dias_asignados', 'dias_disponibles'])
        ->map(function ($periodo) {
            return [
                'id' => (int) $periodo->id,
                'anio' => (int) $periodo->anio,
                'dias_asignados' => (int) $periodo->dias_asignados,
                'dias_disponibles' => (int) $periodo->dias_disponibles,
            ];
        })
        ->values();

    $registros = RegistroDescanso::where('empleado_id', $empleado->id)->where('anio_calendario', $anioActual)->orderBy('mes')->get();
    $diasTomados = $registros->sum('dias_tomados');

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

    return view('empleados.vacaciones', compact('empleado', 'anioActual', 'antiguedadAnios', 'diasDerecho', 'diasTomados', 'diasRestantes', 'meses', 'registroPorMes', 'puestoNombre', 'diasExtra', 'anioPeriodoExtra', 'periodosDisponibles'));
})->name('empleados.vacaciones');

Route::post('/empleados/{empleado}/vacaciones', function (Request $request, Empleado $empleado) {
    if (! session('logeado')) return redirect()->route('login');

    $anioActual = Carbon::now()->year;
    $diasDisponibles = sumarDiasDisponiblesPeriodos($empleado->id);

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

    $diasNuevos = count($selectedDates);

    if ($diasNuevos > $diasDisponibles) {
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
    $tieneMultipleDates = \Illuminate\Support\Facades\Schema::hasColumn('periodos_vacacionales', 'multiple_dates');

    try {
        DB::transaction(function () use ($diasPorMes, $empleado, $anioActual, $inicio, $fin, $request, $selectedDates, $tieneMultipleDates) {
            foreach ($diasPorMes as $mes => $cantidad) {
                $registro = RegistroDescanso::firstOrNew(['empleado_id' => $empleado->id, 'anio_calendario' => $anioActual, 'mes' => $mes]);
                $registro->dias_tomados = ($registro->dias_tomados ?? 0) + $cantidad;
                $registro->save();
            }

            // Calculamos el desglose consumo pasando las fechas específicas
            $desgloseConsumo = consumirDiasDePeriodos($empleado->id, $selectedDates);

            $dataPeriodo = [
                'empleado_id' => $empleado->id,
                'anio_calendario' => $anioActual,
                'fecha_inicio' => $inicio->toDateString(),
                'fecha_fin' => $fin->toDateString(),
                'fecha_regreso' => $fin->copy()->addDay()->toDateString(),
                'dias' => count($selectedDates),
                'observaciones' => $request->input('observaciones'),
                'desglose_consumo' => $desgloseConsumo, // Se guarda el JSON estructurado con las fechas segmentadas
            ];

            if ($tieneMultipleDates) {
                $dataPeriodo['multiple_dates'] = $selectedDates;
            }

            PeriodoVacacional::create($dataPeriodo);
        });
    } catch (\Exception $e) {
        \Log::error('Error al guardar vacaciones', [
            'empleado_id' => $empleado->id,
            'mensaje' => $e->getMessage(),
            'linea' => $e->getLine(),
            'archivo' => $e->getFile(),
        ]);
        return back()->withErrors(['error' => 'Ocurrió un error al guardar el registro.'])->withInput();
    }
    return back()->with('success', 'Registro de días de vacaciones actualizado correctamente.');
})->name('empleados.vacaciones.guardar');
Route::get('/empleados/{empleado}/vacaciones/pdf', function (Request $request, Empleado $empleado) {
    if (! session('logeado')) return redirect()->route('login');

    // 1. Obtener la última solicitud realizada por el empleado
    $periodoSeleccionado = PeriodoVacacional::where('empleado_id', $empleado->id)
        ->whereNull('deleted_at')
        ->orderBy('id', 'desc')
        ->first();

    // Definir el año actual base de la solicitud
    $anioActual = Carbon::now()->year;
    if ($periodoSeleccionado) {
        if (! empty($periodoSeleccionado->fecha_inicio)) {
            $anioActual = Carbon::parse($periodoSeleccionado->fecha_inicio)->year;
        } elseif (! empty($periodoSeleccionado->anio_calendario)) {
            $anioActual = (int) $periodoSeleccionado->anio_calendario;
        }
    }

    // 2. Cálculos globales de antigüedad y derechos
    $antiguedadAnios = (int) floor(Carbon::parse($empleado->fecha_ingreso)->diffInYears(Carbon::now()));
    $ajustesPorAnio = obtenerResumenPeriodos($empleado->id);
    $ajustesUsados = $ajustesPorAnio;
    $diasDerecho = $ajustesPorAnio->sum('dias');
    $diasRestantes = $ajustesPorAnio->sum('restante');

    // 3. Registros de descanso tomados en el año de la solicitud
    $registros = RegistroDescanso::where('empleado_id', $empleado->id)
        ->where('anio_calendario', $anioActual)
        ->orderBy('mes')
        ->get();
    $diasTomados = $registros->sum('dias_tomados');
    $usadoBase = 0;

    // 4. CORRECCIÓN FIFO: Quitar filtro estricto de año para evaluar la cadena completa
    $periodosVacacionales = PeriodoVacacional::where('empleado_id', $empleado->id)
        ->whereNull('deleted_at')
        ->orderBy('id', 'asc')
        ->get();

    if (! $periodoSeleccionado && $periodosVacacionales->count() > 0) {
        $periodoSeleccionado = $periodosVacacionales->sortByDesc('id')->first();
    }

    $periodoAnio = $periodoSeleccionado?->anio_calendario ?? $anioActual;
    $periodoVisual = $periodoAnio;
    $periodoResidual = $ajustesUsados->firstWhere('restante', '>', 0);
    if ($periodoResidual) {
        $periodoVisual = $periodoResidual->anio;
    }

    // 5. CÁLCULO CLAVE: Obtener consumo real desglosado desde el JSON guardado
    $consumoSolicitud = [];
    if ($periodoSeleccionado && !empty($periodoSeleccionado->desglose_consumo)) {
        // Pasar el JSON completo con todas las fechas
        $consumoSolicitud = is_array($periodoSeleccionado->desglose_consumo) 
            ? $periodoSeleccionado->desglose_consumo 
            : (json_decode($periodoSeleccionado->desglose_consumo, true) ?: []);
    } elseif ($periodoSeleccionado) {
        // Fallback para registros antiguos sin desglose
        $consumoConsultado = obtenerConsumoPorPeriodoDeSolicitud($empleado->id, (int) $periodoSeleccionado->id, false);
        $consumoSolicitud = $consumoConsultado->map(function ($item) {
            return [
                'anio' => (int) ($item->anio ?? 0),
                'dias_tomados' => (int) ($item->dias ?? 0),
                'fecha_inicio' => null,
                'fecha_fin' => null,
                'fecha_regreso' => null,
            ];
        })->toArray();
    }

    $meses = [
        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio',
        7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
    ];

    $puesto = $empleado->puesto_id ? Puesto::find($empleado->puesto_id) : null;
    $fecha = Carbon::now();

    $html = view('empleados.pdf', compact('empleado', 'anioActual', 'antiguedadAnios', 'diasDerecho', 'diasTomados', 'diasRestantes', 'registros', 'periodosVacacionales', 'periodoSeleccionado', 'periodoAnio', 'periodoVisual', 'meses', 'puesto', 'fecha', 'ajustesPorAnio', 'ajustesUsados', 'usadoBase', 'consumoSolicitud'))->render();

    $dompdf = new Dompdf;
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    return response($dompdf->output(), 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="vacaciones_'.str_replace(' ', '_', $empleado->nombre.'_'.$empleado->apellido_paterno.'_'.$empleado->apellido_materno).'_'.($periodoAnio ?? $consumoPeriodoSeleccionado->first()->anio ?? $anioActual).'.pdf"',
    ]);
})->name('empleados.vacaciones.pdf');

Route::get('/empleados/{empleado}/vacaciones/historial/pdf', function (Empleado $empleado) {
    if (! session('logeado')) return redirect()->route('login');

    $anioActual = Carbon::now()->year;
    $antiguedadAnios = (int) floor(Carbon::parse($empleado->fecha_ingreso)->diffInYears(Carbon::now()));
    $diasDerecho = sumarDiasAsignadosPeriodos($empleado->id);

    $registros = RegistroDescanso::where('empleado_id', $empleado->id)
        ->where('anio_calendario', $anioActual)
        ->orderBy('mes')
        ->get();
    $diasTomados = $registros->sum('dias_tomados');
    $diasRestantes = sumarDiasDisponiblesPeriodos($empleado->id);

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
        $diasDerecho = sumarDiasAsignadosPeriodos($empleado->id);

        $registros = RegistroDescanso::where('empleado_id', $empleado->id)
            ->where('anio_calendario', $anioActual)
            ->whereNull('deleted_at')
            ->get();
        $diasTomados = $registros->sum('dias_tomados');
        $diasAdeuda = sumarDiasDisponiblesPeriodos($empleado->id);

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

            $diasDisponiblesActuales = sumarDiasDisponiblesPeriodos($empleadoId);
            $disponibles = $diasDisponiblesActuales + (int) $periodo->dias;

            if ($diasNuevos > $disponibles) {
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

            $consumoViejo = obtenerConsumoPorPeriodoDeSolicitud($empleadoId, (int) $periodo->id);
            if ($consumoViejo->isEmpty()) {
                $consumoViejo = collect([
                    (object) [
                        'anio' => (int) ($periodo->anio_calendario ?? $anioActual),
                        'dias' => (int) $periodo->dias,
                    ],
                ]);
            }

            try {
                reintegrarDiasPorConsumoDetallado($empleadoId, $consumoViejo);
            } catch (\Throwable $e) {
                $diasFallback = max(0, (int) collect($consumoViejo)->sum(function ($item) {
                    return (int) ($item->dias ?? 0);
                }));

                if ($diasFallback > 0) {
                    reintegrarDiasAPeriodos($empleadoId, $diasFallback, false);
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

            consumirDiasDePeriodos($empleadoId, $diasNuevos);

            $tieneMultipleDates = \Illuminate\Support\Facades\Schema::hasColumn('periodos_vacacionales', 'multiple_dates');

            $periodo->fecha_inicio = $inicioNuevo->toDateString();
            $periodo->fecha_fin = $finNuevo->toDateString();
            $periodo->dias = $diasNuevos;
            if ($tieneMultipleDates) {
                $periodo->multiple_dates = $selectedDates;
            }
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

            $consumoEliminado = obtenerConsumoPorPeriodoDeSolicitud($empleadoId, (int) $periodo->id);
            if ($consumoEliminado->isEmpty()) {
                $consumoEliminado = collect([
                    (object) [
                        'anio' => (int) ($periodo->anio_calendario ?? $anioActual),
                        'dias' => (int) $periodo->dias,
                    ],
                ]);
            }

            $diasTomados = [];
            if (!empty($periodo->multiple_dates)) {
                $diasTomados = $periodo->multiple_dates;
            } elseif (!empty($periodo->fecha_inicio) && !empty($periodo->fecha_fin)) {
                $inicio = Carbon::parse($periodo->fecha_inicio);
                $fin = Carbon::parse($periodo->fecha_fin);
                for ($fecha = $inicio->copy(); $fecha->lte($fin); $fecha->addDay()) {
                    $diasTomados[] = $fecha->toDateString();
                }
            }

            $diasPorMes = [];
            foreach ($diasTomados as $fechaTomada) {
                try {
                    $mes = Carbon::parse($fechaTomada)->month;
                } catch (\Throwable $e) {
                    continue;
                }

                $diasPorMes[$mes] = ($diasPorMes[$mes] ?? 0) + 1;
            }

            foreach ($diasPorMes as $mes => $cantidad) {
                $registro = RegistroDescanso::where('empleado_id', $empleadoId)
                    ->where('anio_calendario', $anioActual)->where('mes', $mes)->first();
                if ($registro) {
                    $registro->dias_tomados = max(0, $registro->dias_tomados - $cantidad);
                    $registro->save();
                }
            }

            try {
                reintegrarDiasPorConsumoDetallado($empleadoId, $consumoEliminado);
            } catch (\Throwable $e) {
                $diasFallback = max(0, (int) collect($consumoEliminado)->sum(function ($item) {
                    return (int) ($item->dias ?? 0);
                }));

                if ($diasFallback > 0) {
                    reintegrarDiasAPeriodos($empleadoId, $diasFallback, false);
                }
            }

            $periodo->delete();
        });

        return response()->json(['success' => true, 'mensaje' => 'Período eliminado correctamente']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
    }
});