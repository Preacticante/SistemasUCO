<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Puesto; // Asegúrate de importar tu modelo

class PuestoController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $puesto = Puesto::create([
            'nombre' => $request->nombre
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['id' => $puesto->id, 'nombre' => $puesto->nombre], 201);
        }

        return back()->with('success', 'Puesto agregado correctamente.');
    }
}
