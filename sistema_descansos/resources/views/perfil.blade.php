@extends('layouts.app')

@section('title', 'Mi Perfil')
@section('header', 'Perfil de Administrador')

@push('styles')
<style>
    .profile-grid {
        display: grid;
        grid-template-columns: 350px 1fr;
        gap: 25px;
        font-family: 'Inter', sans-serif;
    }

    /* Tarjetas adaptadas a la estética institucional: Bordes curvados y sombra suave */
    .profile-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.04);
        overflow: hidden;
        height: fit-content;
        border: 1px solid #e2e8f0;
        position: relative;
    }

    .profile-card::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 4px; 
        background-color: #AA7F31; /* DORADO INSTITUCIONAL */
    }

    .avatar-section {
        text-align: center;
        margin-bottom: 0;
        padding: 30px;
        background: white;
    }

    .avatar-circle {
        width: 120px;
        height: 120px;
        background-color: #AA7F31; /* DORADO INSTITUCIONAL */
        color: white;
        font-size: 3rem;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin: 0 auto 15px;
        box-shadow: 0 4px 12px rgba(170, 127, 49, 0.3);
    }

    .user-name { font-size: 1.4rem; color: #1e293b; margin: 0; font-weight: 700; }
    .user-role { font-size: 0.9rem; color: #64748b; margin: 5px 0 0; }

    /* Contenedor de datos del perfil lateral */
    .profile-card .info-body {
        padding: 0 30px 30px 30px;
        background: white;
    }

    .info-group { 
        margin-bottom: 20px; 
    }
    .info-label { 
        font-size: 0.8rem; 
        color: #64748b; 
        text-transform: uppercase; 
        letter-spacing: 0.5px; 
        margin-bottom: 6px; 
        font-weight: 700; 
    }
    .info-value { 
        font-size: 1rem; 
        color: #1e293b; 
        font-weight: 500; 
    }

    /* Cabecera idéntica a la tabla de Historial (Verde Pino) */
    .section-title {
        margin: 0;
        background-color: #124416;
        color: white;
        font-size: 0.95rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 18px 25px;
    }

    .section-title i { 
        color: white !important; 
        opacity: 0.9;
    }

    .card-content-body {
        padding: 25px;
        background: white;
    }

    /* Botones adaptados */
    .btn-profile {
        width: 100%;
        padding: 12px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        font-size: 0.9rem;
        transition: 0.2s;
        border-radius: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .btn-primary { 
        background-color: #124416; 
        color: white; 
    }
    .btn-primary:hover { 
        background-color: #0d3310; 
    }

    .btn-outline { 
        background-color: #f8fafc; 
        border: 1px solid #cbd5e1; 
        color: #124416; 
        margin-top: 12px; 
    }
    .btn-outline:hover { 
        background-color: #f1f5f9; 
        border-color: #124416;
    }

    /* Cajas estadísticas usando el morado claro corporativo */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        margin-top: 5px;
    }

    .stat-box {
        background: #f3e8ff; 
        padding: 20px 15px;
        border-radius: 12px;
        text-align: center;
        border: 1px solid #e9d5ff;
        transition: 0.2s;
    }
    
    .stat-box:hover {
        border-color: #AA7F31;
        background-color: #ebd5ff;
    }

    .stat-number { 
        font-size: 1.7rem; 
        font-weight: 700; 
        color: #124416; 
        display: block; 
        margin-bottom: 4px;
    }
    .stat-label { 
        font-size: 0.8rem; 
        color: #475569; 
        font-weight: 600;
    }

    /* Inputs de los Modales */
    .modal-input {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        box-sizing: border-box;
        outline: none;
        font-family: inherit;
        font-size: 0.95rem;
        margin-top: 4px;
    }
    .modal-input:focus {
        border-color: #124416;
    }

    /* --- RESPONSIVO --- */
    @media (max-width: 1024px) {
        .profile-grid { grid-template-columns: 1fr; gap: 2rem; }
    }
    @media (max-width: 768px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .card-content-body > div { grid-template-columns: 1fr !important; gap: 1.5rem !important; }
    }
</style>
@endpush

@section('content')

<!-- CONTENEDOR DE ALERTAS Y NOTIFICACIONES -->
<div>
    @if(session('success'))
        <div style="background-color: #d1e7dd; color: #0f5132; padding: 15px 20px; border-radius: 12px; margin-bottom: 20px; border: 1px solid #badbcc; display: flex; align-items: center; gap: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.02);">
            <i class="fa-solid fa-circle-check" style="font-size: 1.2rem;"></i>
            <span style="font-weight: 500;">{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div style="background-color: #f8d7da; color: #842029; padding: 15px 20px; border-radius: 12px; margin-bottom: 20px; border: 1px solid #f5c2c7; box-shadow: 0 2px 5px rgba(0,0,0,0.02);">
            <div style="font-weight: 700; margin-bottom: 5px; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-circle-exclamation" style="font-size: 1.2rem;"></i>
                <span>Atención: Por favor corrige los siguientes errores</span>
            </div>
            <ul style="margin: 0; padding-left: 20px; font-size: 0.9rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>

<div class="profile-grid">
    
    <!-- BARRA LATERAL IZQUIERDA -->
    <div class="profile-card">
        <div class="avatar-section">
            @php
                $nombre = $usuario->nombre ?? ($usuario->Nombre ?? session('nombre', 'Administrador'));;
                $inicial = substr($nombre, 0, 1);
            @endphp
            <div class="avatar-circle">{{ $inicial }}</div>
            <h2 class="user-name">{{ $nombre }}</h2>
            <p class="user-role">Control de Recursos Humanos</p>
        </div>

        <div class="info-body">
            <div class="info-group">
                <div class="info-label">ID de Acceso</div>
                <div class="info-value">#UCO-2025-001</div>
            </div>

            <div class="info-group">
                <div class="info-label">Estado de Cuenta</div>
                <div class="info-value"><span style="color: #124416; font-weight: bold;">● Activa</span></div>
            </div>

            <button type="button" onclick="abrirModalPassword()" class="btn-profile btn-primary">
                <i class="fa-solid fa-key"></i> Cambiar Contraseña
            </button>
            
            <button type="button" onclick="abrirModalEditar()" class="btn-profile btn-outline">
                <i class="fa-solid fa-pen-to-square"></i> Editar Información
            </button>
        </div>
    </div>

    <!-- CUERPO DE INFORMACIÓN CENTRAL -->
    <div>
        <div class="profile-card" style="margin-bottom: 25px;">
            <h3 class="section-title"><i class="fa-solid fa-address-card"></i> Datos Personales</h3>
            
            <div class="card-content-body">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                    <div class="info-group">
                        <div class="info-label">Nombre Completo</div>
                        <div class="info-value" id="perfil-nombre-visto">{{ session('nombre', 'Administrador') }}</div>
                    </div>
                    <div class="info-group">
                        <div class="info-label">Correo Electrónico</div>
                        <div class="info-value" id="perfil-correo-visto">{{ session('email', 'admin@preparatoria.edu') }}</div>
                    </div>
                    <div class="info-group">
                        <div class="info-label">Departamento</div>
                        <div class="info-value">Administración Educativa</div>
                    </div>
                    <div class="info-group">
                        <div class="info-label">Fecha de Alta</div>
                        <div class="info-value">15 de Enero, 2024</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="profile-card">
            <h3 class="section-title"><i class="fa-solid fa-chart-pie"></i> Resumen de Gestión</h3>
            
            <div class="card-content-body">
                <p style="color: #64748b; font-size: 0.9rem; margin-bottom: 20px; margin-top: 0;">Estadísticas de tu actividad dentro del sistema UCO.</p>
                
                <div class="stats-grid">
                    <div class="stat-box">
                        <!-- Muestra el conteo real de empleados a cargo -->
                        <span class="stat-number">{{ $empleadosACargo }}</span>
                        <span class="stat-label">Empleados a cargo</span>
                    </div>
                    <div class="stat-box">
                        <!-- Muestra la suma real de los días gestionados -->
                        <span class="stat-number">{{ $diasGestionados }}</span>
                        <span class="stat-label">Días gestionados</span>
                    </div>
                    <div class="stat-box">
                        <span class="stat-number">Hoy</span>
                        <span class="stat-label">Último acceso</span>
                    </div>
                    <div class="stat-box">
                        <!-- Muestra el conteo real de reportes generados -->
                        <span class="stat-number">{{ $reportesGenerados }}</span>
                        <span class="stat-label">Reportes generados</span>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============================================== -->
<!-- MODAL 1: CAMBIAR CONTRASEÑA (BLINDADO) -->
<!-- ============================================== -->
<div id="modalPassword" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
    <div style="background: white; width: 100%; max-width: 450px; padding: 30px; border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); position: relative; margin: 20px;">
        <h3 style="margin-top: 0; color: #124416; border-bottom: 2px solid #AA7F31; padding-bottom: 10px; margin-bottom: 20px; font-size: 1.4rem;">
            <i class="fa-solid fa-lock"></i> Actualizar Seguridad
        </h3>
        <form action="{{ Route::has('perfil.password') ? route('perfil.password') : '#' }}" method="POST">
            @csrf
            <div style="margin-bottom: 15px;">
                <label style="color: #475569; font-weight: 600; font-size: 0.9rem;">Contraseña Actual</label>
                <input type="password" name="current_password" required class="modal-input">
            </div>
            <div style="margin-bottom: 15px;">
                <label style="color: #475569; font-weight: 600; font-size: 0.9rem;">Nueva Contraseña</label>
                <input type="password" name="new_password" required class="modal-input">
            </div>
            <div style="margin-bottom: 25px;">
                <label style="color: #475569; font-weight: 600; font-size: 0.9rem;">Confirmar Nueva Contraseña</label>
                <input type="password" name="new_password_confirmation" required class="modal-input">
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="cerrarModalPassword()" style="background: #e2e8f0; color: #475569; border: none; padding: 10px 20px; border-radius: 20px; font-weight: 600; cursor: pointer;">Cancelar</button>
                <button type="submit" style="background: #124416; color: white; border: none; padding: 10px 20px; border-radius: 20px; font-weight: 600; cursor: pointer;">Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================== -->
<!-- MODAL 2: EDITAR INFORMACIÓN (BLINDADO) -->
<!-- ============================================== -->
<div id="modalEditar" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
    <div style="background: white; width: 100%; max-width: 450px; padding: 30px; border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); position: relative; margin: 20px;">
        <h3 style="margin-top: 0; color: #124416; border-bottom: 2px solid #AA7F31; padding-bottom: 10px; margin-bottom: 20px; font-size: 1.4rem;">
            <i class="fa-solid fa-user-gear"></i> Editar Datos de Perfil
        </h3>
        <form action="{{ Route::has('perfil.update') ? route('perfil.update') : '#' }}" method="POST">
            @csrf
            <div style="margin-bottom: 15px;">
                <label style="color: #475569; font-weight: 600; font-size: 0.9rem;">Nombre Completo</label>
                <input type="text" name="name" id="input-edit-nombre" required class="modal-input">
            </div>
            <div style="margin-bottom: 25px;">
                <label style="color: #475569; font-weight: 600; font-size: 0.9rem;">Correo Electrónico</label>
                <input type="email" name="email" id="input-edit-correo" required class="modal-input">
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="cerrarModalEditar()" style="background: #e2e8f0; color: #475569; border: none; padding: 10px 20px; border-radius: 20px; font-weight: 600; cursor: pointer;">Cancelar</button>
                <button type="submit" style="background: #124416; color: white; border: none; padding: 10px 20px; border-radius: 20px; font-weight: 600; cursor: pointer;">Actualizar</button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================== -->
<!-- SCRIPTS DE CONTROL DE INTERACTIVIDAD -->
<!-- ============================================== -->
<script>
    function abrirModalPassword() {
        document.getElementById('modalPassword').style.display = 'flex';
    }
    function cerrarModalPassword() {
        document.getElementById('modalPassword').style.display = 'none';
    }

    function abrirModalEditar() {
        let nombreActual = document.getElementById('perfil-nombre-visto').innerText;
        let correoActual = document.getElementById('perfil-correo-visto').innerText;
        
        document.getElementById('input-edit-nombre').value = nombreActual;
        document.getElementById('input-edit-correo').value = correoActual;

        document.getElementById('modalEditar').style.display = 'flex';
    }
    function cerrarModalEditar() {
        document.getElementById('modalEditar').style.display = 'none';
    }

    window.onclick = function(event) {
        let modalPass = document.getElementById('modalPassword');
        let modalEdit = document.getElementById('modalEditar');
        
        if (event.target == modalPass) cerrarModalPassword();
        if (event.target == modalEdit) cerrarModalEditar();
    }
</script>
@endsection