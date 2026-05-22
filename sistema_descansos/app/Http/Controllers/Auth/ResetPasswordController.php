<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    public function showResetForm(Request $request, $token = null) {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->query('email')]);
    }
    public function reset(Request $request) {
        // lógica para resetear contraseña
    }
}
