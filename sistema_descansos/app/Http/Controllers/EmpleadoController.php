<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    public function index() {
    // Jalamos todos los empleados de la base de datos
    $empleados = \App\Models\Empleado::with('puesto')->get();

    $puestos = \App\Models\Puesto::all();

    return view('empleados.index', compact('empleados', 'puestos'));

    
}
    public function show($id) {
        // lógica para mostrar un empleado
    }
    public function create() {
        // lógica para mostrar formulario de creación
    }
    public function store(Request $request) {
        $validated = $request->validate([
            'nombre' => 'required|string|max:191',
            'apellido_paterno' => 'required|string|max:191',
            'apellido_materno' => 'nullable|string|max:191',
            'fecha_ingreso' => 'required|date',
            'puesto_id' => 'required|exists:puestos,id',
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'apellido_paterno.required' => 'El apellido paterno es obligatorio.',
            'fecha_ingreso.required' => 'La fecha de ingreso es obligatoria.',
            'puesto_id.required' => 'Selecciona un puesto.',
            'puesto_id.exists' => 'El puesto seleccionado no existe.',
        ]);

        try {
            \App\Models\Empleado::create($validated);
            return redirect()->route('empleados.index')->with('success', 'Empleado creado correctamente.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'No se pudo crear el empleado: ' . $e->getMessage()]);
        }
    }
    public function edit($id) {
        // lógica para mostrar formulario de edición
    }
    public function update(Request $request, $id) {
        $validated = $request->validate([
            'nombre' => 'required|string|max:191',
            'apellido_paterno' => 'required|string|max:191',
            'apellido_materno' => 'nullable|string|max:191',
            'fecha_ingreso' => 'required|date',
            'puesto_id' => 'required|exists:puestos,id',
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'apellido_paterno.required' => 'El apellido paterno es obligatorio.',
            'fecha_ingreso.required' => 'La fecha de ingreso es obligatoria.',
            'puesto_id.required' => 'Selecciona un puesto.',
            'puesto_id.exists' => 'El puesto seleccionado no existe.',
        ]);

        try {
            $empleado = \App\Models\Empleado::find($id);
            if (! $empleado) {
                return redirect()->route('empleados.index')->withErrors(['error' => 'Empleado no encontrado.']);
            }

            $empleado->update($validated);
            return redirect()->route('empleados.index')->with('success', 'Empleado actualizado correctamente.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'No se pudo actualizar el empleado: ' . $e->getMessage()]);
        }
    }
    public function destroy($id) {
        try {
            $empleado = \App\Models\Empleado::find($id);
            if (! $empleado) {
                return response()->json(['success' => false, 'message' => 'Empleado no encontrado'], 404);
            }
            $empleado->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
