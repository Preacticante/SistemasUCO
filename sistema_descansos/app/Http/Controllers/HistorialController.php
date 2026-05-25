<?php
use App\Models\Descanso;
namespace App\Http\Controllers;
 
// 2. Modifica tu ruta para que haga la consulta:
Route::get('/historial', function () {
    $historial = Descanso::all(); // Jala los datos del modelo
    
    return view('auth.historial', compact('historial'));
});