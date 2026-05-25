<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        // Traemos las reglas de la ley de vacaciones para mostrarlas si es necesario
        $leyVacaciones = \App\Models\LeyVacacion::orderBy('anios_antiguedad')->get();

        return view('configuracion', compact('leyVacaciones'));
    }

    public function update(Request $request) 
    {
        // lógica para actualizar ajustes
    }
}