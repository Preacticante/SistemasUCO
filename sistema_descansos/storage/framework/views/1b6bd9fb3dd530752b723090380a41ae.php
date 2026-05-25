<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Control de Descansos'); ?> | UCO</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { margin: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f6; }
        .wrapper { display: flex; height: 100vh; overflow: hidden; }
        
        /* Estilos del Menú Lateral */
        .sidebar { width: 260px; background-color: #1e293b; color: white; display: flex; flex-direction: column; flex-shrink: 0; }
        .sidebar-header { padding: 20px; font-size: 1.2rem; font-weight: bold; border-bottom: 1px solid #334155; text-align: center; letter-spacing: 1px;}
        .sidebar-menu { list-style: none; padding: 0; margin: 0; flex: 1; overflow-y: auto; }
        .sidebar-menu li a { display: block; padding: 16px 20px; color: #cbd5e1; text-decoration: none; transition: 0.2s ease-in-out; font-size: 15px;}
        .sidebar-menu li a:hover, .sidebar-menu li a.active { background-color: #334155; color: white; border-left: 4px solid #10b981; }
        .sidebar-menu i { margin-right: 12px; width: 20px; text-align: center; }
        
        /* Estilos del Panel Derecho */
        .main-panel { flex: 1; display: flex; flex-direction: column; overflow-y: auto; }
        .topbar { background-color: #1e293b; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .topbar-title { font-weight: 600; font-size: 1.1rem; }
        .topbar-user a { color: #f87171; text-decoration: none; font-weight: bold; margin-left: 15px; transition: 0.2s;}
        .topbar-user a:hover { color: #ef4444; }
        .content { padding: 30px; }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>

    <div class="wrapper">
        <aside class="sidebar">
            <div class="sidebar-header" style="text-align: center; padding: 20px 20px 10px 20px;">
                
                <div style="padding: 0; margin-bottom: 15px; display: flex; justify-content: center;">
                    <img src="<?php echo e(asset('img/logo_uco.png')); ?>" 
                         alt="Logo UCO" 
                         style="max-height: 190px; width: auto; mix-blend-mode: screen;">
                </div>
                
                <span style="font-size: 1.1rem; letter-spacing: 1px; font-weight: bold;">SISTEMA UCO</span>
            </div>
            <ul class="sidebar-menu">
                <li>
                    <a href="<?php echo e(route('panel')); ?>" class="<?php echo e(request()->routeIs('panel') ? 'active' : ''); ?>">
                        <i class="fa-solid fa-chart-line"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('empleados')); ?>" class="<?php echo e(request()->routeIs('empleados') ? 'active' : ''); ?>">
                        <i class="fa-solid fa-users"></i> Empleados
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('historial')); ?>" class="<?php echo e(request()->routeIs('historial') ? 'active' : ''); ?>">
                        <i class="fa-solid fa-calendar-check"></i> Historial de Vacaciones
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('configuracion')); ?>" class="<?php echo e(request()->routeIs('configuracion') ? 'active' : ''); ?>">
                        <i class="fa-solid fa-gear"></i> Configuración
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('perfil')); ?>" class="<?php echo e(request()->routeIs('perfil') ? 'active' : ''); ?>">
                        <i class="fa-solid fa-user-shield"></i> Mi Perfil
                    </a>
                </li>
            </ul>
        </aside>

        <main class="main-panel">
            <header class="topbar">
                <div class="topbar-title"><?php echo $__env->yieldContent('header', 'Panel de Administración'); ?></div>
                <div class="topbar-user">
                    Hola, <strong><?php echo e(session('nombre', 'Administrador')); ?></strong> 
                    <a href="<?php echo e(route('logout')); ?>"><i class="fa-solid fa-right-from-bracket"></i> Cerrar Sesión</a>
                </div>
            </header>
            
            <div class="content">
                <?php echo $__env->yieldContent('content'); ?> 
            </div>
        </main>
    </div>

</body>
</html><?php /**PATH C:\Users\becario.tie\Documents\GitHub\SistemasUCO\sistema_descansos\resources\views/layouts/app.blade.php ENDPATH**/ ?>