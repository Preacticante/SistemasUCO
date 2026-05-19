<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Models\Usuario;
use App\Models\Empleado;

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
            ? route('password.reset', $token) . '?email=' . urlencode($usuario->correo)
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
    if (!session('logeado')) {
        return redirect()->route('login');
    }

    // Traemos a todos los empleados de la base de datos
    $empleados = Empleado::all();
    
    return view('dashboard', compact('empleados'));
})->name('panel');

// Cerrar Sesión
Route::get('/logout', function () {
    session()->forget(['logeado', 'nombre']);
    return redirect()->route('login');
})->name('logout');