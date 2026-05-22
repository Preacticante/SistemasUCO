<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    public function index() {
        // lógica para listar empleados
    }
    public function show($id) {
        // lógica para mostrar un empleado
    }
    public function create() {
        // lógica para mostrar formulario de creación
    }
    public function store(Request $request) {
        // lógica para guardar empleado
    }
    public function edit($id) {
        // lógica para mostrar formulario de edición
    }
    public function update(Request $request, $id) {
        // lógica para actualizar empleado
    }
    public function destroy($id) {
        // lógica para eliminar empleado
    }
}
