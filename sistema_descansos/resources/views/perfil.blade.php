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

    .profile-card {
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        height: fit-content;
                    border-bottom: 4px solid #AA7F31; /* Detalle Dorado UCO */

    }

    /* Estilos del Avatar (Dorado UCO) */
    .avatar-section {
        text-align: center;
        margin-bottom: 25px;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 25px;
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
        box-shadow: 0 4px 10px rgba(170, 127, 49, 0.3); /* Sombra dorada */
    }

    .user-name { font-size: 1.4rem; color: #1e293b; margin: 0; font-weight: 700; }
    .user-role { font-size: 0.9rem; color: #64748b; margin: 5px 0 0; }

    /* Secciones de Detalles */
    .info-group { margin-bottom: 20px; }
    .info-label { font-size: 0.8rem; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px; font-weight: 600; }
    .info-value { font-size: 1rem; color: #334155; font-weight: 500; }

    .section-title {
        font-size: 1.1rem;
        color: #1e293b;
        margin: 0 0 20px 0;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 700;
    }

    .section-title i { color: #340C51; /* MORADO INSTITUCIONAL */ }

    /* Botones (Verde UCO) */
    .btn-profile {
        width: 100%;
        padding: 12px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        transition: 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .btn-primary { background-color: #124416; color: white; } /* VERDE INSTITUCIONAL */
    .btn-primary:hover { background-color: #0d3310; }

    .btn-outline { background-color: white; border: 1px solid #cbd5e1; color: #64748b; margin-top: 10px; }
    .btn-outline:hover { background-color: #f8fafc; color: #1e293b; }

    /* Tarjetas de Resumen */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        margin-top: 25px;
    }

    .stat-box {
        background: #f8fafc;
        padding: 15px;
        border-radius: 10px;
        text-align: center;
        border: 1px solid #f1f5f9;
        transition: 0.3s;
    }
    
    .stat-box:hover {
        border-color: #AA7F31; /* DORADO INSTITUCIONAL */
        box-shadow: 0 4px 6px rgba(170, 127, 49, 0.05);
    }

    .stat-number { font-size: 1.5rem; font-weight: 700; color: #340C51; display: block; } /* MORADO INSTITUCIONAL */
    .stat-label { font-size: 0.75rem; color: #64748b; }
</style>
@endpush

@section('content')
<div class="profile-grid">
    
    <div class="profile-card">
        <div class="avatar-section">
            @php
                $nombre = session('nombre', 'Administrador');
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

            <button class="btn-profile btn-primary">
                <i class="fa-solid fa-key"></i> Cambiar Contraseña
            </button>
            
            <button class="btn-profile btn-outline">
                <i class="fa-solid fa-pen-to-square"></i> Editar Información
            </button>
        </div>
    </div>

    <div>
        <div class="profile-card" style="margin-bottom: 25px;">
            <h3 class="section-title"><i class="fa-solid fa-address-card"></i> Datos Personales</h3>
            
            <div class="card-content-body">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                    <div class="info-group">
                        <div class="info-label">Nombre Completo</div>
                        <div class="info-value">{{ session('nombre', 'Administrador') }}</div>
                    </div>
                    <div class="info-group">
                        <div class="info-label">Correo Electrónico</div>
                        <div class="info-value">admin@preparatoria.edu</div>
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
                        <span class="stat-number">17</span>
                        <span class="stat-label">Empleados a cargo</span>
                    </div>
                    <div class="stat-box">
                        <span class="stat-number">282</span>
                        <span class="stat-label">Días gestionados</span>
                    </div>
                    <div class="stat-box">
                        <span class="stat-number">Hoy</span>
                        <span class="stat-label">Último acceso</span>
                    </div>
                    <div class="stat-box">
                        <span class="stat-number">4</span>
                        <span class="stat-label">Reportes generados</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

{{-- ESTILOS AL FINAL DEL TODO --}}
@push('styles')
<style>
    .profile-grid {
        display: grid;
        grid-template-columns: 350px 1fr;
        gap: 25px;
        font-family: 'Inter', sans-serif;
    }

    /* Adaptado a la estética institucional: Bordes curvados y sombra suave */
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
        background-color: #AA7F31; /* El color DORADO INSTITUCIONAL que usas en tu sistema */
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
                border-radius: 24px;

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
        border-radius: 8px;
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
        background: #f3e8ff; /* Morado claro idéntico al contenedor de tu tabla */
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

    /* --- RESPONSIVO --- */
    @media (max-width: 1024px) {
        .profile-grid {
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        .profile-card {
            height: auto;
        }

        .avatar-circle {
            width: 100px;
            height: 100px;
            font-size: 2.5rem;
        }

        .user-name {
            font-size: 1.2rem;
        }
    }

    @media (max-width: 768px) {
        .profile-grid {
            gap: 1.5rem;
        }

        .profile-card {
            border-radius: 12px;
        }

        .avatar-section {
            padding: 1.5rem;
        }

        .profile-card .info-body {
            padding: 0 1.5rem 1.5rem 1.5rem;
        }

        .card-content-body {
            padding: 1.5rem;
        }

        .section-title {
            padding: 1rem 1.5rem;
            font-size: 0.9rem;
            gap: 8px;
        }

        .avatar-circle {
            width: 90px;
            height: 90px;
            font-size: 2rem;
            margin-bottom: 12px;
        }

        .user-name {
            font-size: 1rem;
        }

        .user-role {
            font-size: 0.8rem;
        }

        .info-label {
            font-size: 0.75rem;
            margin-bottom: 4px;
        }

        .info-value {
            font-size: 0.9rem;
        }

        .btn-profile {
            padding: 0.7rem;
            font-size: 0.85rem;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-top: 0;
        }

        .stat-box {
            padding: 1rem;
        }

        .stat-number {
            font-size: 1.4rem;
        }

        .stat-label {
            font-size: 0.7rem;
        }

        .card-content-body > p {
            font-size: 0.8rem;
            margin-bottom: 1rem !important;
        }

        .card-content-body > div {
            gap: 1.5rem !important;
        }
    }

    @media (max-width: 480px) {
        .profile-grid {
            gap: 1rem;
        }

        .profile-card {
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        }

        .avatar-section {
            padding: 1rem;
        }

        .profile-card .info-body {
            padding: 0 1rem 1rem 1rem;
        }

        .card-content-body {
            padding: 1rem;
        }

        .avatar-circle {
            width: 80px;
            height: 80px;
            font-size: 1.8rem;
            margin-bottom: 10px;
        }

        .user-name {
            font-size: 0.95rem;
        }

        .user-role {
            font-size: 0.75rem;
            margin-top: 3px;
        }

        .info-group {
            margin-bottom: 12px;
        }

        .info-label {
            font-size: 0.65rem;
            margin-bottom: 3px;
            letter-spacing: 0;
        }

        .info-value {
            font-size: 0.85rem;
        }

        .section-title {
            padding: 0.8rem 1rem;
            font-size: 0.8rem;
            gap: 6px;
        }

        .section-title i {
            font-size: 0.9rem;
        }

        .btn-profile {
            padding: 0.6rem;
            font-size: 0.8rem;
            gap: 6px;
        }

        .btn-profile i {
            font-size: 0.85rem;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .stat-box {
            padding: 0.75rem;
            border-radius: 8px;
        }

        .stat-number {
            font-size: 1.1rem;
            margin-bottom: 2px;
        }

        .stat-label {
            font-size: 0.65rem;
        }

        .card-content-body > p {
            font-size: 0.75rem;
            margin-bottom: 0.8rem !important;
            margin-top: 0 !important;
        }

        .card-content-body > div {
            grid-template-columns: 1fr !important;
            gap: 1rem !important;
        }
    }
</style>
@endpush