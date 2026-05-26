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

        <div class="info-group">
            <div class="info-label">ID de Acceso</div>
            <div class="info-value">#UCO-2025-001</div>
        </div>

        <div class="info-group">
            <div class="info-label">Estado de Cuenta</div>
            <div class="info-value"><span style="color: #124416; font-weight: bold;">● Activa</span></div> </div>

        <button class="btn-profile btn-primary">
            <i class="fa-solid fa-key"></i> Cambiar Contraseña
        </button>
        
        <button class="btn-profile btn-outline">
            <i class="fa-solid fa-pen-to-square"></i> Editar Información
        </button>
    </div>

    <div>
        <div class="profile-card" style="margin-bottom: 25px;">
            <h3 class="section-title"><i class="fa-solid fa-address-card"></i> Datos Personales</h3>
            
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

        <div class="profile-card">
            <h3 class="section-title"><i class="fa-solid fa-chart-pie"></i> Resumen de Gestión</h3>
            <p style="color: #64748b; font-size: 0.9rem; margin-bottom: 20px;">Estadísticas de tu actividad dentro del sistema UCO.</p>
            
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
@endsection