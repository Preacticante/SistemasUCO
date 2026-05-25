<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show() {
    return view('perfil');
}
    public function update(Request $request) {
        // lógica para actualizar perfil
    }
}
