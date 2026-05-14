<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso | Control de Descansos</title>
    <style>
        body { font-family: sans-serif; background-color: #f4f7f6; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-box { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 100%; max-width: 350px; }
        h2 { text-align: center; color: #333; }
        .form-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: .5rem; color: #666; }
        input { width: 100%; padding: .5rem; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: .7rem; background-color: #2c3e50; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 1rem; margin-top: 1rem; }
        button:hover { background-color: #34495e; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Control de Descansos</h2>
        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Correo Electrónico</label>
                <input type="email" name="correo" required placeholder="admin@preparatoria.edu">
            </div>
            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" name="contrasena" required placeholder="********">
            </div>
            <button type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>