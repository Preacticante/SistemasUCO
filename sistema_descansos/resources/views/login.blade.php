<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso | Control de Descansos</title>
    
</head>
<body>
    <div class="login-box">
        <h2>Centro de control</h2>
        <p>Inicia sesión con tu correo institucional para acceder al panel de gestión.</p>

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

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="correo">Correo Electrónico</label>
                <input id="correo" type="email" name="correo" required placeholder="admin@preparatoria.edu">
            </div>
            <div class="form-group">
                <label for="contrasena">Contraseña</label>
                <input id="contrasena" type="password" name="contrasena" required placeholder="********">
            </div>
            <button type="submit">Entrar</button>
        </form>

        <p class="help-text"><a href="{{ route('password.request') }}">Olvidé mi contraseña</a></p>
    </div>
</body>
</html>
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
            align-items: center;
            justify-content: center;
            background: radial-gradient(circle at top, #6a86ff 0%, #3a5797 35%, #1d2a4c 100%);
            color: #1f324f;
        }

        .login-box {
            width: min(480px, calc(100% - 2rem));
            background: #ffffff;
            border-radius: 28px;
            padding: 2rem;
            box-shadow: 0 32px 90px rgba(7, 28, 74, 0.18);
        }

        .login-box h2 {
            margin: 0 0 0.95rem;
            font-size: 1.9rem;
            color: #000000;
            text-align: center;
        }

        .login-box p {
            margin: 0 0 1.75rem;
            color: #6d7b96;
            line-height: 1.6;
        }

        .alert {
            margin-bottom: 1rem;
            padding: 1rem 1.25rem;
            border-radius: 16px;
            background: #f0f7ff;
            color: #1a3a70;
            border: 1px solid #dce8ff;
        }

        .error-list {
            margin-bottom: 1.25rem;
            padding: 0.9rem 1.1rem;
            border-radius: 14px;
            background: #ffe8e8;
            color: #853535;
            border: 1px solid #f2c6c6;
        }

        .form-group {
            margin-bottom: 1.4rem;
        }

        label {
            display: block;
            margin-bottom: 0.55rem;
            color: #000000;
            font-size: 0.95rem;
        }

        input {
            width: 100%;
            padding: 0.95rem 1rem;
            font-size: 1rem;
            border: 1px solid #d7dee9;
            border-radius: 14px;
            background: #f8fbff;
            color: #20304a;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        input:focus {
            outline: none;
            border-color: #6a86ff;
            box-shadow: 0 0 0 4px rgba(106, 134, 255, 0.12);
        }

        button {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 14px;
            background: linear-gradient(135deg, #5567ff 0%, #2744d5 100%);
            color: #ffffff;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        button:hover {
            transform: translateY(-1px);
            box-shadow: 0 16px 30px rgba(58, 87, 151, 0.18);
        }

        .help-text {
            margin-top: 1rem;
            font-size: 0.95rem;
            color: #627693;
            text-align: center;
        }

        .help-text a {
            color: #3c62f5;
            text-decoration: none;
        }
    </style>