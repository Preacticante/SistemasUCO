<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Control de Descansos') | UCO</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Estilos Base */
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
            background-color: #340C51; /* MORADO INSTITUCIONAL */
            color: white;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 15px rgba(0,0,0,0.05);
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
            transition: 0.3s;
            font-size: 0.95rem;
            border-left: 4px solid transparent;
        }

        .sidebar-menu li a i {
            margin-right: 15px;
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }

        .sidebar-menu li a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }

        /* --- Elemento Activo (Dorado UCO) --- */
        .sidebar-menu li a.active {
            background-color: rgba(170, 127, 49, 0.15); /* Dorado con transparencia */
            color: white;
            border-left: 4px solid #AA7F31; /* DORADO INSTITUCIONAL */
            font-weight: 600;
        }

        .sidebar-menu li a.active i {
            color: #AA7F31; /* DORADO INSTITUCIONAL */
        }

        /* Contenido Principal */
        .main-content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        /* Topbar */
        .topbar {
            background-color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
            border-bottom: 1px solid #f1f5f9;
        }

        .content-area {
            padding: 30px;
            flex-grow: 1;
        }
    </style>
    @stack('styles')
</head>
<body>

    <div class="wrapper">
        <aside class="sidebar">
            <div class="sidebar-header" style="text-align: center; padding: 20px 20px 10px 20px;">
                
                <div style="padding: 0; margin-bottom: 15px; display: flex; justify-content: center;">
                    <img src="{{ asset('img/logo_uco.png') }}" 
                         alt="Logo UCO" 
                         style="max-height: 190px; width: auto; mix-blend-mode: screen;">
                </div>
                
                <span style="font-size: 1.1rem; letter-spacing: 1px; font-weight: bold;">SISTEMA UCO</span>
            </div>
            <ul class="sidebar-menu">
                <li>
                    <a href="{{ route('panel') }}" class="{{ request()->routeIs('panel') ? 'active' : '' }}">
                        <i class="fa-solid fa-chart-line"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('empleados') }}" class="{{ request()->routeIs('empleados') ? 'active' : '' }}">
                        <i class="fa-solid fa-users"></i> Empleados
                    </a>
                </li>
                <li>
                    <a href="{{ route('historial') }}" class="{{ request()->routeIs('historial') ? 'active' : '' }}">
                        <i class="fa-solid fa-calendar-check"></i> Historial de Vacaciones
                    </a>
                </li>
                <li>
                    <a href="{{ route('configuracion') }}" class="{{ request()->routeIs('configuracion') ? 'active' : '' }}">
                        <i class="fa-solid fa-gear"></i> Configuración
                    </a>
                </li>
                <li>
                    <a href="{{ route('perfil') }}" class="{{ request()->routeIs('perfil') ? 'active' : '' }}">
                        <i class="fa-solid fa-user-shield"></i> Mi Perfil
                    </a>
                </li>
            </ul>
        </aside>

        <main class="main-panel">
            <header class="topbar">
                <div class="topbar-title">@yield('header', 'Panel de Administración')</div>
                <div class="topbar-user">
                    Hola, <strong>{{ session('nombre', 'Administrador') }}</strong> 
                    <a href="{{ route('logout') }}"><i class="fa-solid fa-right-from-bracket"></i> Cerrar Sesión</a>
                </div>
            </header>
            
            <div class="content">
                @yield('content') 
            </div>
        </main>
    </div>

</body>
</html>