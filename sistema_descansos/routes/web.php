<?php

use App\Models\Empleado;
use App\Models\LeyVacacion;
use App\Models\RegistroDescanso;
use App\Models\Usuario;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

// Pantalla del Login
Route::get('/', function () {
    return view('login');
})->name('login');

// Procesar el Login
Route::post('/login', function (Request $request) {
    $usuario = Usuario::where('correo', $request->correo)->first();

    if ($usuario && Hash::check($request->contrasena, $usuario->contrasena)) {
        session(['logeado' => true, 'nombre' => $usuario->nombre_completo]);

        return redirect()->route('panel');
    } else {
        return back()->withErrors(['error' => 'Correo o contraseña incorrectos']);
    }
})->name('login.post');

// Formulario de olvido de contraseña
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

Route::post('/forgot-password', function (Request $request) {
    $request->validate([
        'correo' => 'required|email',
    ]);

    $usuario = Usuario::where('correo', $request->correo)->first();

    if ($usuario) {
        $token = Str::random(64);
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $usuario->correo],
            ['token' => Hash::make($token), 'created_at' => now()]
        );

        $status = 'Si el correo existe, te hemos enviado un enlace para restablecer tu contraseña.';
        $resetLink = app()->environment('local')
            ? route('password.reset', $token).'?email='.urlencode($usuario->correo)
            : null;

        return back()->with('status', $status)->with('reset_link', $resetLink);
    }

    return back()->with('status', 'Si el correo existe, te hemos enviado un enlace para restablecer tu contraseña.');
})->name('password.email');

Route::get('/reset-password/{token}', function (Request $request, $token) {
    $email = $request->query('email');

    if (! $email) {
        return redirect()->route('password.request')->withErrors(['correo' => 'Necesitamos tu correo para validar el enlace.']);
    }

    $record = DB::table('password_reset_tokens')->where('email', $email)->first();

    if (! $record || ! Hash::check($token, $record->token) || Carbon::parse($record->created_at)->lt(now()->subMinutes(config('auth.passwords.users.expire')))) {
        return redirect()->route('password.request')->withErrors(['token' => 'El enlace de restablecimiento no es válido o ha expirado.']);
    }

    return view('auth.reset-password', ['token' => $token, 'email' => $email]);
})->name('password.reset');

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'correo' => 'required|email',
        'token' => 'required|string',
        'contrasena' => 'required|string|min:8|confirmed',
    ]);

    $record = DB::table('password_reset_tokens')->where('email', $request->correo)->first();

    if (! $record || ! Hash::check($request->token, $record->token) || Carbon::parse($record->created_at)->lt(now()->subMinutes(config('auth.passwords.users.expire')))) {
        return back()->withErrors(['token' => 'El enlace de restablecimiento no es válido o ha expirado.']);
    }

    $usuario = Usuario::where('correo', $request->correo)->first();

    if (! $usuario) {
        return back()->withErrors(['correo' => 'No se encontró el correo.']);
    }

    $usuario->contrasena = Hash::make($request->contrasena);
    $usuario->save();

    DB::table('password_reset_tokens')->where('email', $request->correo)->delete();

    return redirect()->route('login')->with('status', 'Contraseña actualizada con éxito. Ahora puedes iniciar sesión.');
})->name('password.update');

// Pantalla del Panel Principal Dashboard
Route::get('/panel', function () {
    if (! session('logeado')) {
        return redirect()->route('login');
    }

    $anioActual = Carbon::now()->year;
    $empleados = Empleado::all();

    $empleadosResumen = $empleados->map(function ($empleado) use ($anioActual) {
        $antiguedadAnios = Carbon::parse($empleado->fecha_ingreso)->diffInYears(Carbon::now());
        $ley = LeyVacacion::where('anios_antiguedad', '<=', $antiguedadAnios)
            ->orderBy('anios_antiguedad', 'desc')
            ->first();
        $diasDerecho = $ley?->dias_derecho ?? 0;
        $diasTomados = RegistroDescanso::where('empleado_id', $empleado->id)
            ->where('anio_calendario', $anioActual)
            ->sum('dias_tomados');

        return (object) [
            'empleado' => $empleado,
            'antiguedadAnios' => $antiguedadAnios,
            'diasDerecho' => $diasDerecho,
            'diasTomados' => $diasTomados,
            'diasRestantes' => max(0, $diasDerecho - $diasTomados),
        ];
    });

    $totalEmpleados = $empleadosResumen->count();
    $totalDiasDerecho = $empleadosResumen->sum('diasDerecho');
    $totalDiasTomados = $empleadosResumen->sum('diasTomados');
    $totalDiasRestantes = $empleadosResumen->sum('diasRestantes');
    $empleadosConMenosDias = $empleadosResumen->sortBy('diasRestantes')->take(5);

    return view('dashboard', compact(
        'empleados',
        'anioActual',
        'totalEmpleados',
        'totalDiasDerecho',
        'totalDiasTomados',
        'totalDiasRestantes',
        'empleadosConMenosDias'
    ));
})->name('panel');

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
        return back()->withErrors(['fecha_inicio' => "No puedes registrar más de {$diasDerecho} días en el año {$anioActual}."])->withInput();
    }

    $diasPorMes = [];
    for ($fecha = $inicio->copy(); $fecha->lte($fin); $fecha->addDay()) {
        $mes = $fecha->month;
        $diasPorMes[$mes] = ($diasPorMes[$mes] ?? 0) + 1;
    }

    foreach ($diasPorMes as $mes => $cantidad) {
        $registro = RegistroDescanso::firstOrNew([
            'empleado_id' => $empleado->id,
            'anio_calendario' => $anioActual,
            'mes' => $mes,
        ]);

        $registro->dias_tomados = ($registro->dias_tomados ?? 0) + $cantidad;
        $registro->save();
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

    $meses = [
        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
        5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
        9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
    ];

    $html = view('empleados.pdf', compact(
        'empleado',
        'anioActual',
        'antiguedadAnios',
        'diasDerecho',
        'diasTomados',
        'diasRestantes',
        'registros',
        'meses'
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
