<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso | Control de Descansos UCO</title>
    
</head>
<body>

    <div class="login-box">
        <?php
            $path = public_path('img/logo_uco.png');
            $type = pathinfo($path, PATHINFO_EXTENSION);
            if(file_exists($path)){
                $data = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                echo '<img src="'.$base64.'" alt="UCO PREPA CONTEMPORÁNEA" class="logo">';
            }
        ?>

        <h2>Centro de Control</h2>
        <p class="subtitle">Inicia sesión con tu cuenta de administrador para acceder al panel de gestión de recursos humanos.</p>

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
                <input id="contrasena" type="password" name="contrasena" required placeholder="••••••••">
            </div>
            <button type="submit">Acceder al Sistema</button>
        </form>
        <p class="help-text"><a href="<?php echo e(route('password.request')); ?>">¿Olvidaste tu contraseña?</a></p>
    </div>

</body>
</html>

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
            /* Degradado radial elegante (Verde Pino UCO con iluminación) */
            background: radial-gradient(circle at top left, #1a5c1e 0%, #124416 50%, #082109 100%);
        }
        
        .login-box {
            /* Efecto Cristal (Glassmorphism) sutil */
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            padding: 45px 40px;
            border-radius: 16px;
            width: 100%;
            max-width: 420px;
            text-align: center;
            /* Sombra más pronunciada para despegar la tarjeta del fondo */
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .logo { 
            height: 180px; 
            margin-bottom: 5px; 
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.05));
        }
        
        h2 { 
            margin: 0 0 10px; 
            font-size: 1.6rem; 
            font-weight: 800; 
            color: #124416;
        }
        
        .subtitle { 
            color: #64748b; 
            font-size: 0.9rem; 
            margin-bottom: 30px; 
            line-height: 1.5; 
        }
        
        .form-group { 
            margin-bottom: 20px; 
            text-align: left; 
        }
        
        .form-group label { 
            display: block; 
            font-size: 0.85rem; 
            font-weight: 700; 
            color: #475569;
            margin-bottom: 8px; 
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .form-group input {
            width: 100%; 
            padding: 14px; 
            border: 1px solid #cbd5e1;
            border-radius: 10px; 
            font-size: 0.95rem; 
            outline: none; 
            transition: all 0.3s ease;
            background-color: #f8fafc;
            color: #1e293b;
        }
        
        .form-group input:focus { 
            border-color: #AA7F31; 
            background-color: #ffffff;
            box-shadow: 0 0 0 4px rgba(170, 127, 49, 0.15);
        }
        
        button[type="submit"] {
            width: 100%; 
            padding: 14px; 
            background-color: #AA7F31; 
            color: white;
            border: none; 
            border-radius: 24px; 
            font-size: 1rem; 
            font-weight: 700;
            cursor: pointer; 
            margin-top: 10px; 
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(170, 127, 49, 0.3);
        }
        
        button[type="submit"]:hover { 
            background-color: #8c6827; 
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(170, 127, 49, 0.4);
        }
        
        .help-text { 
            margin-top: 25px; 
            font-size: 0.85rem; 
            font-weight: 600;
        }
        
        .help-text a { 
            color: #124416; 
            text-decoration: none; 
            transition: color 0.2s;
        }
        
        .help-text a:hover { 
            color: #AA7F31; 
        }
        
        .error-list {
            background-color: #fef2f2; 
            color: #dc2626; 
            padding: 12px 15px;
            border-radius: 10px; 
            margin-bottom: 25px; 
            font-size: 0.85rem; 
            text-align: left;
            border: 1px solid #fecaca;
        }
    </style><?php /**PATH /var/www/html/resources/views/auth/login.blade.php ENDPATH**/ ?>