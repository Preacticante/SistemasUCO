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


// Librerías
use Dompdf\Dompdf;


// | RUTAS DE AUTENTICACIÓN

Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

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
    $query = PeriodoVacacional::with('empleado')->orderBy('fecha_inicio', 'desc');

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
// Usuarios: página y endpoints AJAX (Modificado para Cuentas del Sistema)
Route::get('/perfiles', function () {
    if (! session('logeado')) return redirect()->route('login');
    return view('usuarios.index'); // Mantiene la vista lila que ya adaptamos
})->name('perfiles.index');

Route::get('/perfiles/list', [UsuariosController::class, 'list'])->name('perfiles.list');
Route::post('/perfiles', [UsuariosController::class, 'store'])->name('perfiles.store');
Route::put('/perfiles/{id}', [UsuariosController::class, 'update'])->name('perfiles.update');
Route::delete('/perfiles/{id}', [UsuariosController::class, 'destroy'])->name('perfiles.destroy');
Route::post('/perfil/update', [ProfileController::class, 'update'])->name('perfil.update');
Route::post('/perfil/password', [ProfileController::class, 'changePassword'])->name('perfil.password');


// | RUTAS DE LÓGICA DE VACACIONES

Route::get('/empleados/{empleado}/vacaciones', function (Empleado $empleado) {
    if (! session('logeado')) return redirect()->route('login');

    $anioActual = Carbon::now()->year;
    $antiguedadAnios = Carbon::parse($empleado->fecha_ingreso)->diffInYears(Carbon::now());
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

    return view('empleados.vacaciones', compact('empleado', 'anioActual', 'antiguedadAnios', 'diasDerecho', 'diasTomados', 'diasRestantes', 'meses', 'registroPorMes'));
})->name('empleados.vacaciones');

Route::post('/empleados/{empleado}/vacaciones', function (Request $request, Empleado $empleado) {
    if (! session('logeado')) return redirect()->route('login');

    $anioActual = Carbon::now()->year;
    $antiguedadAnios = Carbon::parse($empleado->fecha_ingreso)->diffInYears(Carbon::now());
    $ley = LeyVacacion::where('anios_antiguedad', '<=', $antiguedadAnios)->orderBy('anios_antiguedad', 'desc')->first();
    $diasDerecho = $ley?->dias_derecho ?? 0;

    $validator = Validator::make($request->all(), [
        'fecha_inicio' => 'required|date',
        'fecha_fin'    => 'required|date|after_or_equal:fecha_inicio',
    ]);

    if ($validator->fails()) return redirect()->back()->withErrors($validator)->withInput();
    
    $inicio = Carbon::parse($request->fecha_inicio);
    $fin = Carbon::parse($request->fecha_fin);

    if ($inicio->year !== $anioActual || $fin->year !== $anioActual) return back()->withErrors(['fecha_inicio' => "Las fechas deben estar dentro del año {$anioActual}."])->withInput();

    $registroPorMes = RegistroDescanso::where('empleado_id', $empleado->id)->where('anio_calendario', $anioActual)->get();
    $diasTomadosActuales = $registroPorMes->sum('dias_tomados');
    $diasNuevos = $inicio->diffInDays($fin) + 1;

    if ($diasTomadosActuales + $diasNuevos > $diasDerecho) return back()->withErrors(['fecha_inicio' => "No puedes registrar, aún no cuentas con suficientes días."])->withInput();

    $diasPorMes = [];
    for ($fecha = $inicio->copy(); $fecha->lte($fin); $fecha->addDay()) {
        $mes = $fecha->month;
        $diasPorMes[$mes] = ($diasPorMes[$mes] ?? 0) + 1;
    }

    $diasPeriodo = $inicio->diffInDays($fin) + 1;

    try {
        DB::transaction(function () use ($diasPorMes, $empleado, $anioActual, $inicio, $fin, $request, $diasPeriodo) {
            foreach ($diasPorMes as $mes => $cantidad) {
                $registro = RegistroDescanso::firstOrNew(['empleado_id' => $empleado->id, 'anio_calendario' => $anioActual, 'mes' => $mes]);
                $registro->dias_tomados = ($registro->dias_tomados ?? 0) + $cantidad;
                $registro->save();
            }
            PeriodoVacacional::create([
                'empleado_id' => $empleado->id, 'anio_calendario' => $anioActual,
                'fecha_inicio' => $request->fecha_inicio, 'fecha_fin' => $request->fecha_fin,
                'fecha_regreso' => $fin->copy()->addDay()->toDateString(), 'dias' => $diasPeriodo,
            ]);
        });
    } catch (\Exception $e) {
        return back()->withErrors(['error' => 'Ocurrió un error al guardar el registro.'])->withInput();
    }
    return back()->with('success', 'Registro de días de vacaciones actualizado correctamente.');
})->name('empleados.vacaciones.guardar');

Route::get('/empleados/{empleado}/vacaciones/pdf', function (Empleado $empleado) {
    if (! session('logeado')) return redirect()->route('login');

    $anioActual = Carbon::now()->year;
    $antiguedadAnios = Carbon::parse($empleado->fecha_ingreso)->diffInYears(Carbon::now());
    $ley = LeyVacacion::where('anios_antiguedad', '<=', $antiguedadAnios)->orderBy('anios_antiguedad', 'desc')->first();
    $diasDerecho = $ley?->dias_derecho ?? 0;

    $registros = RegistroDescanso::where('empleado_id', $empleado->id)->where('anio_calendario', $anioActual)->orderBy('mes')->get();
    $diasTomados = $registros->sum('dias_tomados');
    $diasRestantes = max(0, $diasDerecho - $diasTomados);

    $periodosVacacionales = PeriodoVacacional::where('empleado_id', $empleado->id)->orderBy('fecha_inicio')->get();

    $meses = [
        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 5 => 'Mayo', 6 => 'Junio', 
        7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
    ];

    $puesto = $empleado->puesto_id ? Puesto::find($empleado->puesto_id) : null;
    $fecha = Carbon::now();

    $html = view('empleados.pdf', compact('empleado', 'anioActual', 'antiguedadAnios', 'diasDerecho', 'diasTomados', 'diasRestantes', 'registros', 'periodosVacacionales', 'meses', 'puesto', 'fecha'))->render();

    $dompdf = new Dompdf;
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    return response($dompdf->output(), 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="vacaciones_'.str_replace(' ', '_', $empleado->nombre.'_'.$empleado->apellido_paterno.'_'.$empleado->apellido_materno).'_'.$anioActual.'.pdf"',
    ]);
})->name('empleados.vacaciones.pdf');


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
        'dias' => $periodo->dias
    ]);
});

Route::put('/periodos/{id}', function (Request $request, $id) {
    if (! session('logeado')) return response()->json(['error' => 'No autorizado'], 401);

    $validator = Validator::make($request->all(), [
        'fecha_inicio' => 'required|date',
        'fecha_fin'    => 'required|date|after_or_equal:fecha_inicio',
    ]);

    if ($validator->fails()) return response()->json(['error' => 'Validación de datos fallida.'], 422);

    try {
        DB::transaction(function () use ($request, $id) {
            $periodo = PeriodoVacacional::findOrFail($id);
            $empleadoId = $periodo->empleado_id;
            $anioActual = $periodo->anio_calendario;

            $inicioNuevo = Carbon::parse($request->fecha_inicio);
            $finNuevo = Carbon::parse($request->fecha_fin);

            if ($inicioNuevo->year !== $anioActual || $finNuevo->year !== $anioActual) {
                throw new \Exception("Las fechas editadas deben estar dentro del año {$anioActual}.");
            }

            $diasNuevos = $inicioNuevo->diffInDays($finNuevo) + 1;

            $empleado = Empleado::find($empleadoId);
            $antiguedadAnios = Carbon::parse($empleado->fecha_ingreso)->diffInYears(Carbon::now());
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

            // 1. REVERTIR DÍAS VIEJOS
            $inicioViejo = Carbon::parse($periodo->fecha_inicio);
            $finViejo = Carbon::parse($periodo->fecha_fin);
            $diasPorMesViejos = [];
            
            for ($fecha = $inicioViejo->copy(); $fecha->lte($finViejo); $fecha->addDay()) {
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

            // 2. APLICAR DÍAS NUEVOS
            $diasPorMesNuevos = [];
            for ($fecha = $inicioNuevo->copy(); $fecha->lte($finNuevo); $fecha->addDay()) {
                $mes = $fecha->month;
                $diasPorMesNuevos[$mes] = ($diasPorMesNuevos[$mes] ?? 0) + 1;
            }

            foreach ($diasPorMesNuevos as $mes => $cantidad) {
                $registro = RegistroDescanso::firstOrNew(['empleado_id' => $empleadoId, 'anio_calendario' => $anioActual, 'mes' => $mes]);
                $registro->dias_tomados = ($registro->dias_tomados ?? 0) + $cantidad;
                $registro->save();
            }

            // 3. ACTUALIZAR EL PERIODO
            $periodo->fecha_inicio = $request->fecha_inicio;
            $periodo->fecha_fin = $request->fecha_fin;
            $periodo->dias = $diasNuevos;
            $periodo->fecha_regreso = $finNuevo->copy()->addDay()->toDateString();
            $periodo->save(); 
        });

        return response()->json(['success' => true, 'mensaje' => 'Período actualizado correctamente']);
    } catch (\Exception $e) {
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

            for ($fecha = $inicio->copy(); $fecha->lte($fin); $fecha->addDay()) {
                $mes = $fecha->month;
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
            $periodo->delete();
        });

        return response()->json(['success' => true, 'mensaje' => 'Período eliminado correctamente']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
    }
});