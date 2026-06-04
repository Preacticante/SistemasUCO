<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title'); ?> | Sistema UCO</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <?php echo $__env->yieldPushContent('styles'); ?>
    
    <style>
        body {
            margin: 0;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
        }

        .app-container {
            display: flex;
            min-height: 100vh;
        }

        /* --- Menú Lateral (Morado UCO) --- */
        .sidebar {
            width: 260px;
            background-color: #124416; /* MORADO INSTITUCIONAL */
            color: white;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
        }

        .sidebar-header {
            text-align: center;
            padding: 20px 20px 10px 20px;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li a {
            display: flex;
            align-items: center;
            padding: 16px 25px;
            color: #e2e8f0;
            text-decoration: none;
            font-size: 0.95rem;
            border-left: 4px solid transparent;
            transition: all 0.3s ease; /* Hace que el movimiento sea suave */
        }

        .sidebar-menu li a i {
            margin-right: 15px;
            width: 20px;
            text-align: center;
        }

        /* EFECTO DE BRINCO AL PASAR EL RATÓN */
        .sidebar-menu li a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateX(10px); /* Esto hace que la opción brinque a la derecha */
            color: white;
        }

        /* --- Elemento Activo (Dorado UCO) --- */
        .sidebar-menu li a.active {
            background-color: rgba(170, 127, 49, 0.15);
            color: white;
            border-left: 4px solid #AA7F31; /* DORADO INSTITUCIONAL */
            font-weight: bold;
        }

        .sidebar-menu li a.active i {
            color: #AA7F31; /* DORADO INSTITUCIONAL */
        }

        .main-content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            width: calc(100% - 260px);
        }

        /* --- Barra Superior / Topbar (Morado UCO) --- */
        .topbar {
            background-color: #124416; /* MORADO INSTITUCIONAL */
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255,255,255,0.1); /* Línea divisoria sutil */
            color: white;
        }

        .topbar div:first-child {
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        .topbar-user {
            color: #e2e8f0;
        }

        .topbar-user strong {
            color: white;
        }

        .topbar-user a {
            color: #AA7F31; /* DORADO INSTITUCIONAL */
            text-decoration: none;
            margin-left: 15px;
            font-weight: 600;
            padding: 5px 10px;
            border-radius: 4px;
            transition: 0.2s;
        }

        .topbar-user a:hover {
            background-color: rgba(170, 127, 49, 0.1);
        }

        .content-area {
            padding: 30px;
            flex-grow: 1;
        }

        /* Forzar el Verde UCO en botones genéricos de las tablas */
        .btn-success, 
        .bg-green-500,
        button[style*="background-color: green"],
        a[style*="background-color: green"],
        .text-green-500 {
            background-color: #124416 !important; /* VERDE INSTITUCIONAL */
            border-color: #124416 !important;
            color: white !important;
        }
    </style>
</head>
<body>

    <div class="app-container">
        
        <aside class="sidebar">
            <div class="sidebar-header">
                <div style="padding: 0; margin-bottom: 45px; display: flex; justify-content: center;">
                    <img src="<?php echo e(asset('img/logo_uco1.png')); ?>" alt="Logo UCO" style="max-height: 110px; mix-blend-mode: screen;">
                </div>
                <span style="font-size: 1.1rem; font-weight: bold; color: white;">SISTEMA UCO</span>
            </div>
            
            <ul class="sidebar-menu">
                <li>
                    <a href="<?php echo e(route('panel')); ?>" class="<?php echo e(request()->routeIs('panel') ? 'active' : ''); ?>">
                        <i class="fa-solid fa-chart-line"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('empleados.index')); ?>" class="<?php echo e(request()->routeIs('empleados.*') ? 'active' : ''); ?>">
                        <i class="fa-solid fa-users"></i> Empleados
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(route('historial')); ?>" class="<?php echo e(request()->routeIs('historial') ? 'active' : ''); ?>">
                        <i class="fa-solid fa-calendar-check"></i> Historial de Vacaciones
                    </a>
                </li>
                
                <li>
                    <a href="<?php echo e(route('perfil')); ?>" class="<?php echo e(request()->routeIs('perfil') ? 'active' : ''); ?>">
                        <i class="fa-solid fa-user"></i> Mi Perfil
                    </a>
                </li>
            </ul>
        </aside>

        <main class="main-content">
            <header class="topbar">
                <div><?php echo $__env->yieldContent('header'); ?></div>
                <div class="topbar-user">
                    Hola, <strong><?php echo e(session('nombre', 'Administrador')); ?></strong>
                    <a href="<?php echo e(route('logout')); ?>"><i class="fa-solid fa-right-from-bracket"></i> Cerrar Sesión</a>
                </div>
            </header>
            
            <div class="content-area">
                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </main>
        
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH /var/www/html/resources/views/layouts/app.blade.php ENDPATH**/ ?>