<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Empleado | Sistema de Descansos</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #a6bce9; margin: 0; }
        .navbar { background-color: #1f2937; color: white; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; position: relative; }
        .navbar .nav-left { display: flex; align-items: center; gap: 14px; }
        .navbar h1 { font-size: 18px; margin: 0; color: #ffffff; }
        .hamburger { width: 30px; height: 22px; display: inline-flex; flex-direction: column; justify-content: space-between; cursor: pointer; }
        .hamburger span { display: block; height: 3px; background: white; border-radius: 999px; }
        .navbar a { color: #f87171; text-decoration: none; font-weight: bold; }
        .sidebar { position: fixed; top: 0; left: 0; width: 250px; height: 100%; background: #111827; color: #f8fafc; padding: 20px; transform: translateX(-100%); transition: transform 0.25s ease-in-out; z-index: 1000; }
        .sidebar.open { transform: translateX(0); }
        .sidebar h2 { margin: 0 0 20px; font-size: 20px; color: #a6bce9; }
        .sidebar a { display: block; color: #e5e7eb; text-decoration: none; padding: 10px 0; border-bottom: 1px solid rgba(229,231,235,0.2); }
        .sidebar a:hover { color: #ffffff; }
        .overlay { position: fixed; inset: 0; background: rgba(15,23,42,0.5); opacity: 0; visibility: hidden; transition: opacity 0.25s ease-in-out; z-index: 900; }
        .overlay.open { opacity: 1; visibility: visible; }
        .container { max-width: 1000px; margin: 30px auto; background: white; padding: 24px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        h1 { color: #374151; font-size: 26px; border-bottom: 2px solid #e5e7eb; padding-bottom: 10px; margin-bottom: 20px; }
        .grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; }
        .card { background: #ffffff; border: 1px solid #e5e7eb; border-radius: 8px; padding: 18px; }
        .card h2 { margin-top: 0; color: #111827; }
        .field { margin-bottom: 14px; }
        .field label { display: block; color: #4b5563; font-weight: 600; margin-bottom: 6px; }
        .field input { width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; }
        .btn-primary { background-color: #2563eb; color: white; padding: 10px 16px; border-radius: 6px; text-decoration: none; border: none; cursor: pointer; }
        .btn-primary:hover { background-color: #1d4ed8; }
        .btn-secondary { background-color: #e5e7eb; color: #111827; padding: 10px 16px; border-radius: 6px; text-decoration: none; display: inline-block; margin-bottom: 16px; }
        .alert { background: #d1fae5; color: #065f46; border: 1px solid #10b981; padding: 12px 14px; border-radius: 6px; margin-bottom: 18px; }
        .error-list { background: #fef2f2; color: #b91c1c; border: 1px solid #fca5a5; padding: 12px 14px; border-radius: 6px; margin-bottom: 18px; }
        .summary { background: #f8fafc; border: 1px solid #d1d5db; border-radius: 8px; padding: 16px; margin-top: 20px; }
        .summary p { margin: 8px 0; }
        .summary strong { color: #111827; }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="nav-left">
            <button class="hamburger" id="menuToggle" aria-label="Abrir menú">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <h1>Control de Descansos UCO</h1>
        </div>
        <div>
            Hola, <?php echo e(session('nombre')); ?> |
            <a href="<?php echo e(route('logout')); ?>">Cerrar Sesión</a>
        </div>
    </div>

    <div class="sidebar" id="sidebar">
        <h2>Menú</h2>
        <a href="<?php echo e(route('panel')); ?>">Panel Principal</a>
        <a href="<?php echo e(route('empleados.pdf', $empleado->id)); ?>">Exportar PDF</a>
        <a href="<?php echo e(route('logout')); ?>">Cerrar Sesión</a>
    </div>
    <div class="overlay" id="overlay"></div>

    <div class="container">
        <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 18px;">
            <a href="<?php echo e(route('panel')); ?>" class="btn-secondary">← Volver al Panel</a>
            <a href="<?php echo e(route('empleados.pdf', $empleado->id)); ?>" class="btn-primary" style="background-color: #dc2626;">Exportar PDF</a>
        </div>
        <h1>Editar vacaciones del trabajador</h1>

        <?php if($errors->any()): ?>
            <div class="error-list">
                <strong>Errores:</strong>
                <ul>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if(session('success')): ?>
            <div class="alert"><?php echo e(session('success')); ?></div>
        <?php endif; ?>

        <div class="card">
            <h2>Datos del trabajador</h2>
            <div class="grid">
                <div>
                    <p><strong>ID:</strong> <?php echo e($empleado->id); ?></p>
                </div>
                <div>
                    <p><strong>Nombre:</strong> <?php echo e($empleado->nombre_completo); ?></p>
                </div>
                <div>
                    <p><strong>Fecha de ingreso:</strong> <?php echo e($empleado->fecha_ingreso); ?></p>
                </div>
                <div>
                    <p><strong>Puesto:</strong> <?php echo e($puesto?->nombre ?? 'Sin puesto asignado'); ?></p>
                </div>
                <div>
                    <p><strong>Años de antigüedad:</strong> <?php echo e($antiguedad); ?></p>
                </div>
            </div>
        </div>

        <div class="card" style="margin-top: 20px;">
            <h2>Asignación de vacaciones</h2>
            <form action="<?php echo e(route('empleados.actualizar', $empleado->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="grid">
                    <div class="field">
                        <label for="fecha_inicio">Fecha de inicio</label>
                        <input type="date" id="fecha_inicio" name="fecha_inicio" value="<?php echo e(old('fecha_inicio')); ?>" required>
                    </div>
                    <div class="field">
                        <label for="fecha_fin">Fecha de fin</label>
                        <input type="date" id="fecha_fin" name="fecha_fin" value="<?php echo e(old('fecha_fin')); ?>" required>
                    </div>
                </div>
                <button type="submit" class="btn-primary">Guardar asignación</button>
            </form>
        </div>

        <div class="card" style="margin-top: 20px;">
            <h2>Días pendientes</h2>
            <p><strong>Días de derecho anual:</strong> <?php echo e($diasDerecho); ?></p>
            <p><strong>Días ya tomados:</strong> <?php echo e($diasTomados); ?></p>
            <p><strong>Días pendientes:</strong> <?php echo e($diasPendientes); ?></p>
        </div>

        <?php if(session('vacacionesAsignadas')): ?>
            <?php $vacacionesAsignadas = session('vacacionesAsignadas'); ?>
            <div class="summary">
                <h2>Resumen de la asignación</h2>
                <p><strong>Fecha de inicio:</strong> <?php echo e($vacacionesAsignadas['inicio']); ?></p>
                <p><strong>Fecha de fin:</strong> <?php echo e($vacacionesAsignadas['fin']); ?></p>
                <p><strong>Días asignados:</strong> <?php echo e($vacacionesAsignadas['dias']); ?></p>
            </div>
        <?php endif; ?>
    </div>

    <script>
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');

        function toggleMenu() {
            sidebar.classList.toggle('open');
            overlay.classList.toggle('open');
        }

        menuToggle.addEventListener('click', toggleMenu);
        overlay.addEventListener('click', toggleMenu);
    </script>
</body>
</html>
<?php /**PATH C:\Users\becario.tie\Documents\GitHub\SistemasUCO\sistema_descansos\resources\views/empleados/editar.blade.php ENDPATH**/ ?>