<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm() {
        return view('auth.forgot-password');
    }
    public function sendResetLinkEmail(Request $request) {
        // lógica para enviar enlace
    }
}
