<?php

use App\Models\Empleado;
use App\Models\LeyVacacion;
use App\Models\RegistroDescanso;
use App\Models\PeriodoVacacional;
use App\Models\Usuario;
use App\Models\Puesto;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\EmpleadoController;

// Login
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Recuperar y restablecer contraseña
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

// Dashboard
Route::get('/panel', [DashboardController::class, 'index'])->name('panel');

// Perfil
Route::get('/perfil', [ProfileController::class, 'show'])->name('perfil.show');
Route::post('/perfil', [ProfileController::class, 'update'])->name('perfil.update');

// Ajustes
Route::get('/ajustes', [SettingsController::class, 'index'])->name('ajustes.index');
Route::post('/ajustes', [SettingsController::class, 'update'])->name('ajustes.update');

// Empleados (CRUD básico)
Route::resource('empleados', EmpleadoController::class);

Route::get('/empleados/{empleado}/vacaciones', function (Empleado $empleado) {
    if (! session('logeado')) {
        return redirect()->route('login');
    }

    $anioActual = Carbon::now()->year;
    $antiguedadAnios = Carbon::parse($empleado->fecha_ingreso)->diffInYears(Carbon::now());
    $ley = LeyVacacion::where('anios_antiguedad', '<=', $antiguedadAnios)
        ->orderBy('anios_antiguedad', 'desc')
        ->first();

    $diasDerecho = $ley?->dias_derecho ?? 0;
    $registros = RegistroDescanso::where('empleado_id', $empleado->id)
        ->where('anio_calendario', $anioActual)
        ->orderBy('mes')
        ->get();

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

    return view('empleados.vacaciones', compact(
        'empleado',
        'anioActual',
        'antiguedadAnios',
        'diasDerecho',
        'diasTomados',
        'diasRestantes',
        'meses',
        'registroPorMes'
    ));
})->name('empleados.vacaciones');

Route::post('/empleados/{empleado}/vacaciones', function (Request $request, Empleado $empleado) {
    if (! session('logeado')) {
        return redirect()->route('login');
    }

    $anioActual = Carbon::now()->year;
    $antiguedadAnios = Carbon::parse($empleado->fecha_ingreso)->diffInYears(Carbon::now());
    $ley = LeyVacacion::where('anios_antiguedad', '<=', $antiguedadAnios)
        ->orderBy('anios_antiguedad', 'desc')
        ->first();
    $diasDerecho = $ley?->dias_derecho ?? 0;

    $request->validate([
        'fecha_inicio' => 'required|date',
        'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
    ]);

    $inicio = Carbon::parse($request->fecha_inicio);
    $fin = Carbon::parse($request->fecha_fin);

    if ($inicio->year !== $anioActual || $fin->year !== $anioActual) {
        return back()->withErrors(['fecha_inicio' => "Las fechas deben estar dentro del año {$anioActual}."])->withInput();
    }

    $registroPorMes = RegistroDescanso::where('empleado_id', $empleado->id)
        ->where('anio_calendario', $anioActual)
        ->get();
    $diasTomadosActuales = $registroPorMes->sum('dias_tomados');
    $diasNuevos = $inicio->diffInDays($fin) + 1;

    if ($diasTomadosActuales + $diasNuevos > $diasDerecho) {
        return back()->withErrors(['fecha_inicio' => "No puedes registrar aun no cuentas con suficientes días para el año {$anioActual}."])->withInput();
    }

    // Calcular días por mes
    $diasPorMes = [];
    for ($fecha = $inicio->copy(); $fecha->lte($fin); $fecha->addDay()) {
        $mes = $fecha->month;
        $diasPorMes[$mes] = ($diasPorMes[$mes] ?? 0) + 1;
    }

    // Validación extra: no permitir 0 o negativos
    $diasPeriodo = $inicio->diffInDays($fin) + 1;
    if ($diasPeriodo <= 0) {
        return back()->withErrors(['fecha_inicio' => 'Rango de fechas inválido.'])->withInput();
    }

    // Recalcular restantes por si hace falta
    $diasRestantes = max(0, $diasDerecho - $diasTomadosActuales);
    if ($diasPeriodo > $diasRestantes) {
        return back()->withErrors(['fecha_inicio' => "No tienes suficientes días disponibles (restantes: {$diasRestantes})."])->withInput();
    }

    // Usar transacción para evitar cambios parciales en caso de error
    try {
        DB::transaction(function () use ($diasPorMes, $empleado, $anioActual, $inicio, $fin, $request, $diasPeriodo) {
            foreach ($diasPorMes as $mes => $cantidad) {
                $registro = RegistroDescanso::firstOrNew([
                    'empleado_id' => $empleado->id,
                    'anio_calendario' => $anioActual,
                    'mes' => $mes,
                ]);

                $registro->dias_tomados = ($registro->dias_tomados ?? 0) + $cantidad;
                $registro->save();
            }

            $fechaRegreso = $fin->copy()->addDay()->toDateString();

            PeriodoVacacional::create([
                'empleado_id' => $empleado->id,
                'anio_calendario' => $anioActual,
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin,
                'fecha_regreso' => $fechaRegreso,
                'dias' => $diasPeriodo,
            ]);
        });
    } catch (\Exception $e) {
        return back()->withErrors(['error' => 'Ocurrió un error al guardar el registro. Intenta de nuevo.'])->withInput();
    }

    return back()->with('success', 'Registro de días de vacaciones actualizado correctamente.');
})->name('empleados.vacaciones.guardar');

Route::get('/empleados/{empleado}/vacaciones/pdf', function (Empleado $empleado) {
    if (! session('logeado')) {
        return redirect()->route('login');
    }

    $anioActual = Carbon::now()->year;
    $antiguedadAnios = Carbon::parse($empleado->fecha_ingreso)->diffInYears(Carbon::now());
    $ley = LeyVacacion::where('anios_antiguedad', '<=', $antiguedadAnios)
        ->orderBy('anios_antiguedad', 'desc')
        ->first();
    $diasDerecho = $ley?->dias_derecho ?? 0;

    $registros = RegistroDescanso::where('empleado_id', $empleado->id)
        ->where('anio_calendario', $anioActual)
        ->orderBy('mes')
        ->get();
    $diasTomados = $registros->sum('dias_tomados');
    $diasRestantes = max(0, $diasDerecho - $diasTomados);

    // Obtener periodos vacacionales de la base de datos
    $periodosVacacionales = PeriodoVacacional::where('empleado_id', $empleado->id)
        ->orderBy('fecha_inicio')
        ->get();

    $meses = [
        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
        5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
        9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
    ];

    $puesto = $empleado->puesto_id ? Puesto::find($empleado->puesto_id) : null;
    $fecha = Carbon::now();

    $html = view('empleados.pdf', compact(
        'empleado',
        'anioActual',
        'antiguedadAnios',
        'diasDerecho',
        'diasTomados',
        'diasRestantes',
        'registros',
        'periodosVacacionales',
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
        'Content-Disposition' => 'inline; filename="vacaciones_'.str_replace(' ', '_', $empleado->nombre_completo).'_'.$anioActual.'.pdf"',
    ]);
})->name('empleados.vacaciones.pdf');

// Cerrar Sesión
Route::get('/logout', function () {
    session()->forget(['logeado', 'nombre']);

    return redirect()->route('login');
})->name('logout');
