<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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