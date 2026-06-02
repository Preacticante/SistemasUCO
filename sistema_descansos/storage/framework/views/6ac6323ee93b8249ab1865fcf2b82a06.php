<?php $__env->startSection('title', 'Configuración'); ?>
<?php $__env->startSection('header', 'Configuración del Sistema'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .config-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 25px;
        font-family: 'Inter', sans-serif;
        
        
    }
    
    /* 1. Ajustado el redondeado general y corregido el padding */
    .config-card {
        background: white;
        padding: 0; /* Quitamos el padding de aquí para que la barra verde toque los bordes */
        border-radius: 16px; /* Bordes notablemente más redondos como en la imagen */
        box-shadow: 0 4px 15px rgba(0,0,0,0.04);
        border-bottom: 5px solid #AA7F31; /* Detalle Dorado UCO */
        overflow: hidden; /* ¡CLAVE! Esto recorta todo lo que intente salirse de las esquinas redondas */
    }

    /* 2. Modificado para que sea una barra completa y redondeada arriba */
    .config-card h3 {
        margin: 0;
        background-color: #124416; /* Verde pino */
        color: white;
        font-size: 0.95rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 10px;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 20px;

        padding: 15px 25px; /* Añadimos el padding directamente aquí */
        /* Redondeamos solo las esquinas superiores para acoplarse a la tarjeta */
        border-top-left-radius: 16px;
        border-top-right-radius: 16px;
    }

    /* El icono en color blanco */
    .config-card h3 i {
        color: white;
        opacity: 0.9;
    }

    /* 3. Contenedor de las filas con el padding correcto */
    .config-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 0;
        padding: 22px 25px; /* Espaciado interno elegante */
        border-bottom: 1px solid #f1f5f9; 
    }

    /* Elimina la línea divisoria a la última fila */
    .config-row:last-child {
        border-bottom: none;
    }

    .config-info h4 {
        margin: 0 0 5px 0;
        color: #1e293b;
        font-size: 0.95rem;
        font-weight: 600;
    }

    .config-info p {
        margin: 0;
        color: #64748b;
        font-size: 0.85rem;
    }

    /* Estilos para los Selects */
    .config-select {
        padding: 8px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 8px; /* Un poco más redondo */
        background-color: #f8fafc;
        color: #334155;
        font-size: 0.9rem;
        outline: none;
        cursor: pointer;
        transition: 0.2s;
    }

    .config-select:focus {
        border-color: #124416;
    }

    /* Estilos para los Interruptores (Toggles) */
    .switch {
        position: relative;
        display: inline-block;
        width: 46px;
        height: 24px;
    }
    .switch input { 
        opacity: 0;
        width: 0;
        height: 0;
    }
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: #340C51; /* Color desactivado de tu diseño */
        transition: .4s;
        border-radius: 24px;
    }
    .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }
    input:checked + .slider {
        background-color: #AA7F31; /* Color activado dorado */
    }
    input:checked + .slider:before {
        transform: translateX(22px);
    }

    /* Botones de acción */
    .btn-config {
        background-color: #f8fafc;
        color: #124416;
        border: 1px solid #cbd5e1;
        padding: 8px 15px;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        font-size: 0.85rem;
        transition: 0.2s;
    }
    .btn-config:hover {
        background-color: #f1f5f9;
        border-color: #124416;
    }

    /* --- RESPONSIVO --- */
    @media (max-width: 1200px) {
        .config-grid {
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }
    }

    @media (max-width: 768px) {
        .config-grid {
            grid-template-columns: 1fr;
            gap: 1.2rem;
        }

        .config-card {
            border-radius: 12px;
        }

        .config-card h3 {
            padding: 1rem 1.2rem;
            font-size: 0.85rem;
            gap: 8px;
        }

        .config-row {
            padding: 1rem 1.2rem;
            flex-direction: row;
            gap: 1rem;
        }

        .config-info h4 {
            font-size: 0.9rem;
            margin-bottom: 3px;
        }

        .config-info p {
            font-size: 0.8rem;
        }

        .config-select {
            padding: 0.5rem 0.8rem;
            font-size: 0.85rem;
            min-width: 120px;
        }

        .btn-config {
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
            white-space: nowrap;
        }

        .switch {
            width: 44px;
            height: 22px;
        }

        .slider:before {
            height: 16px;
            width: 16px;
        }

        input:checked + .slider:before {
            transform: translateX(20px);
        }
    }

    @media (max-width: 480px) {
        .config-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .config-card {
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        }

        .config-card h3 {
            padding: 0.8rem 1rem;
            font-size: 0.8rem;
            gap: 6px;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .config-card h3 i {
            font-size: 0.95rem;
        }

        .config-row {
            padding: 0.8rem 1rem;
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .config-row:last-child {
            border-bottom: none;
        }

        .config-info h4 {
            font-size: 0.8rem;
            margin-bottom: 2px;
        }

        .config-info p {
            font-size: 0.7rem;
            color: #94a3b8;
        }

        .config-select {
            width: 100%;
            padding: 0.5rem 0.8rem;
            font-size: 0.8rem;
        }

        .btn-config {
            width: 100%;
            padding: 0.6rem 0.8rem;
            font-size: 0.75rem;
            text-align: center;
        }

        .switch {
            width: 42px;
            height: 20px;
            align-self: flex-end;
            margin-top: -2rem;
        }

        .slider {
            border-radius: 20px;
        }

        .slider:before {
            height: 14px;
            width: 14px;
            left: 2px;
            bottom: 2px;
        }

        input:checked + .slider:before {
            transform: translateX(18px);
        }
    }

    @media (max-width: 320px) {
        .config-card h3 {
            padding: 0.7rem 0.8rem;
            font-size: 0.75rem;
        }

        .config-row {
            padding: 0.6rem 0.8rem;
        }

        .config-info h4 {
            font-size: 0.75rem;
        }

        .config-info p {
            font-size: 0.65rem;
            display: none;
        }

        .config-select,
        .btn-config {
            font-size: 0.7rem;
            padding: 0.4rem 0.6rem;
        }

        .switch {
            width: 40px;
            height: 18px;
        }

        .slider:before {
            height: 12px;
            width: 12px;
        }

        input:checked + .slider:before {
            transform: translateX(16px);
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="config-grid">
        
        <div class="config-card">
            <h3><i class="fa-solid fa-palette"></i> Apariencia y Accesibilidad</h3>
            
            <div class="config-row">
                <div class="config-info">
                    <h4>Modo Oscuro</h4>
                    <p>Cambia el tema visual del sistema.</p>
                </div>
                <label class="switch">
                    <input type="checkbox">
                    <span class="slider"></span>
                </label>
            </div>

            <div class="config-row">
                <div class="config-info">
                    <h4>Idioma del Sistema</h4>
                    <p>Selecciona tu idioma de preferencia.</p>
                </div>
                <select class="config-select">
                    <option value="es">Español (México)</option>
                    <option value="en">English (US)</option>
                </select>
            </div>

            <div class="config-row">
                <div class="config-info">
                    <h4>Tipografía</h4>
                    <p>Ajusta el estilo de letra.</p>
                </div>
                <select class="config-select">
                    <option value="inter">Inter (Predeterminada)</option>
                    <option value="roboto">Roboto</option>
                    <option value="system">Sistema</option>
                </select>
            </div>
        </div>

        <div class="config-card">
            <h3><i class="fa-solid fa-bell"></i> Notificaciones</h3>
            
            <div class="config-row">
                <div class="config-info">
                    <h4>Alertas de Vacaciones</h4>
                    <p>Avisar cuando alguien solicite días.</p>
                </div>
                <label class="switch">
                    <input type="checkbox" checked>
                    <span class="slider"></span>
                </label>
            </div>

            <div class="config-row">
                <div class="config-info">
                    <h4>Resumen Semanal</h4>
                    <p>Recibir reporte por correo los viernes.</p>
                </div>
                <label class="switch">
                    <input type="checkbox">
                    <span class="slider"></span>
                </label>
            </div>
            
            <div class="config-row">
                <div class="config-info">
                    <h4>Alertas de Antigüedad</h4>
                    <p>Avisar cuando un empleado cumpla años.</p>
                </div>
                <label class="switch">
                    <input type="checkbox" checked>
                    <span class="slider"></span>
                </label>
            </div>
        </div>

        <div class="config-card">
            <h3><i class="fa-solid fa-globe"></i> Parámetros del Sistema</h3>
            
            <div class="config-row">
                <div class="config-info">
                    <h4>Zona Horaria</h4>
                    <p>Define la hora local del servidor.</p>
                </div>
                <select class="config-select">
                    <option value="mx">America/Mexico_City</option>
                    <option value="tj">America/Tijuana</option>
                </select>
            </div>

            <div class="config-row">
                <div class="config-info">
                    <h4>Formato de Fecha</h4>
                    <p>Visualización en tablas y PDFs.</p>
                </div>
                <select class="config-select">
                    <option value="dmy">DD/MM/YYYY</option>
                    <option value="mdy">MM/DD/YYYY</option>
                </select>
            </div>
        </div>

        <div class="config-card">
            <h3><i class="fa-solid fa-scale-balanced"></i> Políticas Laborales</h3>
            
            <div class="config-row">
                <div class="config-info">
                    <h4>Ley de Vacaciones</h4>
                    <p>Tabla de días otorgados por antigüedad.</p>
                </div>
                <button class="btn-config">Modificar Tabla</button>
            </div>

            <div class="config-row">
                <div class="config-info">
                    <h4>Días Festivos UCO</h4>
                    <p>Calendario de días no laborables.</p>
                </div>
                <button class="btn-config">Ver Calendario</button>
            </div>
            
            <div class="config-row">
                <div class="config-info">
                    <h4>Ciclo Actual</h4>
                    <p>Periodo de cálculo vacacional.</p>
                </div>
                <select class="config-select">
                    <option value="2026">2026</option>
                    <option value="2025">2025</option>
                </select>
            </div>
        </div>

    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\becario.tie\Documents\GitHub\SistemasUCO\sistema_descansos\resources\views/configuracion.blade.php ENDPATH**/ ?>