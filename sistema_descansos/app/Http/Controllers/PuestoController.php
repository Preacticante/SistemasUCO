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

    /**
     * Soft-delete a puesto if it's not assigned to active empleados.
     */
    public function destroy($id, Request $request)
    {
        $puesto = Puesto::findOrFail($id);

        // Evitar eliminar puestos que todavía están asignados a empleados activos
        $empleadosCount = \App\Models\Empleado::where('puesto_id', $puesto->id)->whereNull('deleted_at')->count();
        if ($empleadosCount > 0) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'No se puede eliminar: existen empleados asignados a este puesto.'], 409);
            }

            return back()->withErrors(['error' => 'No se puede eliminar: existen empleados asignados a este puesto.']);
        }

        // Soft delete
        $puesto->delete();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Puesto eliminado correctamente.');
    }
}
