<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso | Control de Descansos UCO</title>
    <style>
        :root {
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: #1f324f;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            /* El color de fondo oscuro de tu pantalla */
            background-color: #2b394b; 
        }
        .login-box {
            background: white;
            padding: 40px;
            border-radius: 16px;
            width: 100%;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        .logo-img { height: 180px; margin-bottom: 0px; }
        h2 { margin: 0 0 10px; font-size: 1.5rem; font-weight: bold; }
        .subtitle { color: #64748b; font-size: 0.9rem; margin-bottom: 25px; line-height: 1.4; }
        .form-group { margin-bottom: 20px; text-align: left; }
        .form-group label { display: block; font-size: 0.9rem; font-weight: 600; margin-bottom: 8px; }
        .form-group input {
            width: 100%; padding: 12px; border: 1px solid #d1d5db;
            border-radius: 8px; font-size: 1rem; outline: none; transition: 0.2s;
        }
        .form-group input:focus { border-color: #3b82f6; }
        button[type="submit"] {
            width: 100%; padding: 12px; background-color: #3b82f6; color: white;
            border: none; border-radius: 20px; font-size: 1rem; font-weight: 600;
            cursor: pointer; margin-top: 10px; transition: 0.2s;
        }
        button[type="submit"]:hover { background-color: #2563eb; }
        .help-text { margin-top: 20px; font-size: 0.85rem; }
        .help-text a { color: #3b82f6; text-decoration: none; }
        .help-text a:hover { text-decoration: underline; }
        .error-list {
            background-color: #fee2e2; color: #ef4444; padding: 10px;
            border-radius: 8px; margin-bottom: 20px; font-size: 0.85rem; text-align: left;
        }
    </style>
</head>
<body>

    <div class="login-box">
        <?php
            $path = public_path('img/logo_uco.png');
            $type = pathinfo($path, PATHINFO_EXTENSION);
            if(file_exists($path)){
                $data = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                echo '<img src="'.$base64.'" alt="UCO PREPA CONTEMPORÁNEA" class="logo-img">';
            }
        ?>

        <h2>Centro de control</h2>
        <p class="subtitle">Inicia sesión con tu correo institucional para acceder al panel de gestión.</p>

        <?php if($errors->any()): ?>
            <div class="error-list">
                <ul style="margin: 0; padding-left: 1.25rem;">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?php echo e(route('login.post')); ?>" method="POST">
            <?php echo csrf_field(); ?>
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

        <p class="help-text"><a href="<?php echo e(route('password.request')); ?>">Olvidé mi contraseña</a></p>
    </div>

</body>
</html><?php /**PATH C:\Users\becario.tie\Documents\GitHub\SistemasUCO\sistema_descansos\resources\views/auth/login.blade.php ENDPATH**/ ?>