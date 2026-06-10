<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Olvidé mi contraseña</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;

            background: radial-gradient(
                circle at top,
                #1b7a2e 0%,
                #0f5d22 40%,
                #084817 75%,
                #05360f 100%
            );
        }

        .card {
            width: 100%;
            max-width: 500px;
            background: #fff;
            border-radius: 28px;
            padding: 2.5rem;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.25);
        }

        .card h1 {
            color: #1f2937;
            font-size: 2.2rem;
            margin-bottom: 1rem;
        }

        .card p {
            color: #6b7280;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        .alert {
            background: #e8f5e9;
            color: #1b5e20;
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1rem;
            border: 1px solid #a5d6a7;
        }

        .error-list {
            background: #ffebee;
            color: #c62828;
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1rem;
            border: 1px solid #ef9a9a;
        }

        .error-list ul {
            padding-left: 1.2rem;
        }

        label {
            display: block;
            margin-bottom: .5rem;
            color: #4b5563;
            font-weight: 500;
        }

        input {
            width: 100%;
            padding: 1rem;
            border: 1px solid #d1d5db;
            border-radius: 14px;
            margin-bottom: 1rem;
            font-size: 1rem;
            transition: .2s;
        }

        input:focus {
            outline: none;
            border-color: #0f5d22;
            box-shadow: 0 0 0 4px rgba(15, 93, 34, .15);
        }

        button {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 14px;
            color: white;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            background: linear-gradient(
                135deg,
                #1b7a2e 0%,
                #0f5d22 50%,
                #084817 100%
            );
            transition: .2s;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(15, 93, 34, .3);
        }

        .footer-link {
            text-align: center;
            margin-top: 1.5rem;
            color: #6b7280;
        }

        .footer-link a {
            color: #0f5d22;
            text-decoration: none;
            font-weight: 600;
        }

        .footer-link a:hover {
            text-decoration: underline;
        }

        @media(max-width: 600px) {
            .card {
                margin: 1rem;
                padding: 2rem;
                align-items: center;
            }

            .card h1 {
                font-size: 1.8rem;
                align-self: center;
            }
        }
    </style>
</head>

<body>

    <div class="card">

        <h1>¿Olvidaste tu contraseña?</h1>

        <p>
            Escribe tu correo institucional y te enviaremos un enlace para restablecerla.
        </p>

        <?php if(session('status')): ?>
            <div class="alert">
                <?php echo e(session('status')); ?>

            </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <div class="error-list">
                <ul>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?php echo e(route('password.email')); ?>" method="POST">
            <?php echo csrf_field(); ?>

            <label for="correo">Correo electrónico</label>

            <input
                id="correo"
                type="email"
                name="correo"
                value="<?php echo e(old('correo')); ?>"
                placeholder="admin@preparatoria.edu"
                required
            >

            <button type="submit">
                Enviar enlace
            </button>
        </form>

        <p class="footer-link">
            ¿Ya recuerdas tu contraseña?
            <a href="<?php echo e(route('login')); ?>">
                Inicia sesión
            </a>
        </p>

    </div>

</body>
</html><?php /**PATH /var/www/html/resources/views/auth/forgot-password.blade.php ENDPATH**/ ?>