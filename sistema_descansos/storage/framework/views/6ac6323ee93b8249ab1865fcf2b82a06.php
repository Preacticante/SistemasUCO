

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
    
    /* Adaptado a la estética de la imagen: Bordes redondeados y sombra suave */
    .config-card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
                    border-bottom: 4px solid #AA7F31; /* Detalle Dorado UCO */

    }

    /* Transforma el H3 en la barra superior verde pino sólida de la imagen */
    .config-card h3 {
        margin: 0;
        background-color: #124416; /* Color hexadecimal exacto que calculamos */
        color: white;
        font-size: 0.95rem;
        font-weight: 700;
        text-transform: uppercase; /* Todo en mayúsculas como "HISTORIAL DE..." */
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 10px;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 15px;

    }

    /* El icono ahora se adapta en color blanco para contrastar con el fondo verde */
    .config-card h3 i {
        color: white;
        opacity: 0.9;
    }

    /* Contenedor del contenido interno para dar margen respecto a la barra verde */
    .config-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 0;
        padding: 20px 25px;
        border-bottom: 1px solid #f1f5f9; /* Línea divisoria sutil entre filas */
    }

    /* Elimina la línea divisoria a la última fila de cada tarjeta */
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
        border-radius: 6px;
        background-color: #f8fafc;
        color: #334155;
        font-size: 0.9rem;
        outline: none;
        cursor: pointer;
        transition: 0.2s;
    }

    .config-select:focus {
        border-color: #124416; /* Foco cambia al verde institucional */
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
        background-color: #340C51;
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
        background-color: #AA7F31;
    }
    input:checked + .slider:before {
        transform: translateX(22px);
    }

    /* Botones de acción adaptados al estilo del sistema */
    .btn-config {
        background-color: #f8fafc;
        color: #124416; /* Texto verde */
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