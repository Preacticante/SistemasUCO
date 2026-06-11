<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña | UCO</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { 
            min-height: 100vh; display: flex; justify-content: center; align-items: center;
            background: radial-gradient(circle at top, #1b7a2e 0%, #084817 75%, #05360f 100%);
        }
        .card { 
            width: 100%; max-width: 450px; background: #ffffff; border-radius: 28px; 
            padding: 2.5rem; box-shadow: 0 20px 50px rgba(0, 0, 0, 0.25);
        }
        h1 { color: #1f2937; font-size: 1.8rem; margin-bottom: 1rem; text-align: center; }
        p { color: #6b7280; font-size: 0.95rem; text-align: center; margin-bottom: 2rem; line-height: 1.5; }
        
        /* Estilos de inputs y botones iguales al login */
        input { 
            width: 100%; padding: 1rem; border: 1px solid #d1d5db; border-radius: 14px; 
            margin-bottom: 1rem; font-size: 1rem; transition: .2s;
        }
        input:focus { outline: none; border-color: #0f5d22; box-shadow: 0 0 0 4px rgba(15, 93, 34, .15); }
        
        .btn-enviar { 
            width: 100%; padding: 1rem; border: none; border-radius: 14px; color: white; 
            font-size: 1rem; font-weight: 600; cursor: pointer; background: #0f5d22; margin-bottom: 10px;
        }
        
        .btn-regresar { 
            width: 100%; padding: 1rem; border: 1px solid #d1d5db; border-radius: 14px; 
            color: #4b5563; font-size: 1rem; font-weight: 600; cursor: pointer; 
            background: #f9fafb; text-decoration: none; display: block; text-align: center;
        }
        
        .alert { background: #e8f5e9; color: #1b5e20; padding: 1rem; border-radius: 12px; margin-bottom: 1rem; font-size: 0.9rem; }
        .error-list { background: #ffebee; color: #c62828; padding: 1rem; border-radius: 12px; margin-bottom: 1rem; font-size: 0.9rem; }
    </style>
</head>
<body>
    <div class="card">
        <h1>¿Olvidaste tu contraseña?</h1>
        <p>Escribe tu correo institucional y te enviaremos un enlace para restablecerla.</p>

        @if(session('status'))
            <div class="alert">{{ session('status') }}</div>
        @endif

        @if($errors->any())
            <div class="error-list">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST">
            @csrf
            <input type="email" name="correo" placeholder="Correo electrónico institucional" required>
            <button type="submit" class="btn-enviar">Enviar enlace</button>
            <a href="{{ route('login') }}" class="btn-regresar">Regresar al Login</a>
        </form>
    </div>
</body>
</html>