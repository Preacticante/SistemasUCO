@extends('layouts.app')

@section('title', 'Olvidé mi contraseña')



@section('content')
    <div class="card">
        <h1>¿Olvidaste tu contraseña?</h1>
        <p>Escribe tu correo institucional y te enviaremos un enlace para restablecerla.</p>

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


        <form action="{{ route('password.email') }}" method="POST">
            @csrf
            <label for="correo">Correo electrónico</label>
            <input id="correo" type="email" name="correo" value="{{ old('correo') }}" required placeholder="admin@preparatoria.edu">
            <button type="submit">Enviar enlace</button>
        </form>

        <p class="footer-link">¿Ya recuerdas tu contraseña? <a href="{{ route('login') }}">Inicia sesión</a></p>
    </div>
@endsection

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

        body {ñ
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
            color: #747780;
            line-height: 1.6;
        }

        .alert {
            margin-bottom: 1.25rem;
            padding: 1rem 1.2rem;
            border-radius: 16px;
            background: #f0f7ff;
            color: #1a3a70;
            border: 1px solid #dce8ff;
        }

        .error-list {
            margin: 0 0 1rem;
            padding: 0.8rem 1rem;
            border-radius: 14px;
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
            background: linear-gradient(120deg, #5567ff 0%, #2744d5 100%);
            color: #fff;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform .2s ease, box-shadow .2s ease;
        }

        button:hover {
            transform: translateY(-3px);
            box-shadow: 0 24px 26px rgba(58, 87, 151, 0.18);
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

        .manual-link {
            margin-top: 1rem;
            word-break: break-all;
            font-size: 0.95rem;
            color: #e5e7ee;
        }
    </style>
@endpush