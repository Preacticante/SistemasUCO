@extends('screens.layout')

@section('title', 'Restablecer contraseña')

@push('styles')
<style>
        :root {
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: #1f324f;
            background-color: #eef4fb;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: radial-gradient(circle at top, #6a86ff 0%, #3a5797 35%, #1d2a4c 100%);
            color: #1f324f;
        }

        .card {
            width: min(480px, calc(100% - 2rem));
            background: #ffffff;
            border-radius: 28px;
            padding: 2rem;
            box-shadow: 0 32px 80px rgba(7, 28, 74, 0.18);
        }

        .card h1 {
            margin-top: 0;
            font-size: 2rem;
        }

        .card p {
            margin: 0.5rem 0 1.8rem;
            color: #5f6f8c;
            line-height: 1.6;
        }

        .alert,
        .error-list {
            margin-bottom: 1.25rem;
            border-radius: 16px;
            padding: 1rem 1.2rem;
            font-size: 0.95rem;
        }

        .alert {
            background: #f0f7ff;
            color: #1a3a70;
            border: 1px solid #dce8ff;
        }

        .error-list {
            background: #ffe8e8;
            color: #853535;
            border: 1px solid #f2c6c6;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #5a6a86;
            font-size: .95rem;
        }

        input {
            width: 100%;
            border-radius: 14px;
            border: 1px solid #d7dee9;
            padding: 0.95rem 1rem;
            font-size: 1rem;
            background: #f8fbff;
            color: #20304a;
        }

        input:focus {
            outline: none;
            border-color: #5574ff;
            box-shadow: 0 0 0 4px rgba(85, 116, 255, 0.12);
        }

        button {
            width: 100%;
            border: none;
            border-radius: 14px;
            padding: 1rem;
            background: linear-gradient(135deg, #5567ff 0%, #2744d5 100%);
            color: #fff;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform .2s ease, box-shadow .2s ease;
        }

        button:hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 26px rgba(58, 87, 151, 0.18);
        }

        .footer-link {
            margin-top: 1rem;
            text-align: center;
            color: #5f6f8c;
        }

        .footer-link a {
            color: #3c62f5;
            text-decoration: none;
        }
    </style>
@endpush

@section('content')
    <div class="card">
        <h1>Restablecer contraseña</h1>
        <p>Elige una nueva contraseña para tu cuenta.</p>

        @if(session('status'))
            <div class="alert">{{ session('status') }}</div>
        @endif

        @if($errors->any())
            <div class="error-list">
                <ul style="margin: 0; padding-left: 1.25rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="correo" value="{{ $email }}">

            <label for="contrasena">Nueva contraseña</label>
            <input id="contrasena" type="password" name="contrasena" required placeholder="********">

            <label for="contrasena_confirmation">Confirmar contraseña</label>
            <input id="contrasena_confirmation" type="password" name="contrasena_confirmation" required placeholder="********">

            <button type="submit">Guardar nueva contraseña</button>
        </form>

        <p class="footer-link">¿Recordaste tu contraseña? <a href="{{ route('login') }}">Volver al inicio</a></p>
    </div>
@endsection
