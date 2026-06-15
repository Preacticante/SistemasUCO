<?php $__env->startSection('title', 'Inicio'); ?>
<?php $__env->startSection('header', 'UCO • Control de Personal'); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-container">
    <div class="dashboard-header">
        <h1>Panel Principal</h1>
        <p>Control administrativo de personal UCO</p>
    </div>

    <div class="uco-stats-grid">
        <div class="uco-stat-card">
            <div class="uco-stat-icon-wrapper" style="background: rgba(52, 12, 81, 0.1); color: #340C51;">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="uco-stat-info">
                <span class="uco-stat-label">Total empleados</span><br>
                <strong class="uco-stat-number"><?php echo e($totalEmpleados ?? 0); ?></strong>
            </div>
        </div>
        <div class="uco-stat-card">
            <div class="uco-stat-icon-wrapper" style="background: rgba(170, 127, 49, 0.1); color: #AA7F31;">
                <i class="fa-solid fa-book"></i>
            </div>
            <div class="uco-stat-info">
                <span class="uco-stat-label">Derecho Total</span><br>
                <strong class="uco-stat-number"><?php echo e($totalDiasDerecho ?? 0); ?></strong>
            </div>
        </div>
        <div class="uco-stat-card">
            <div class="uco-stat-icon-wrapper" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">
                <i class="fa-solid fa-calendar-xmark"></i>
            </div>
            <div class="uco-stat-info">
                <span class="uco-stat-label">Tomados</span><br>
                <strong class="uco-stat-number" style="color: #ef4444;"><?php echo e($diasTomadosEsteAnio ?? 0); ?></strong>
            </div>
        </div>
        <div class="uco-stat-card">
            <div class="uco-stat-icon-wrapper" style="background: rgba(18, 68, 22, 0.1); color: #124416;">
                <i class="fa-solid fa-calendar-check"></i>
            </div>
            <div class="uco-stat-info">
                <span class="uco-stat-label">Restantes</span><br>
                <strong class="uco-stat-number" style="color: #124416;"><?php echo e($diasRestantesTotales ?? 0); ?></strong>
            </div>
        </div>
    </div>

    <div class="dashboard-main-content">
        <div class="table-wrapper">
            <table class="table-uco">
                <thead>
                    <tr>
                        <th>Empleado</th>
                        <th>Restantes</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $empleadosConMenosDias ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($item->empleado->nombre); ?></td>
                            <td style="color: #ef4444; font-weight:700;"><?php echo e($item->diasRestantes); ?></td>
                            <td><a href="<?php echo e(route('empleados.vacaciones', $item->empleado->id)); ?>" class="btn-calcular">Ver</a></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        <div class="calendar-card">
            <div class="calendar-card-header">
                <div>
                    <strong>Calendario de eventos</strong>
                    <div class="calendar-legend">
                        <span class="legend-item legend-descanso">Descanso de un trabajador</span>
                        <span class="legend-item legend-festivo">Festivo</span>
                        <span class="legend-item legend-institucional">Vacaciones institucionales</span>
                    </div>
                </div>
                <a href="<?php echo e(route('dias-especiales.index')); ?>" class="btn-calcular">Gestionar días especiales</a>
            </div>
            <div id="calendar"></div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      locale: 'es',
      dayMaxEvents: 2,
      headerToolbar: { left: 'prev,next', center: 'title', right: 'today' },
      events: '/api/eventos-vacaciones',
      height: 'auto'
    });
    calendar.render();

    window.addEventListener('resize', function() {
        calendar.updateSize();
    });
  });
</script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('styles'); ?>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    .dashboard-container { 
        font-family: 'Inter', sans-serif; 
        max-width: 1400px; 
        margin: 0 auto; 
        padding: 20px; 
        box-sizing: border-box;
    }
    
    /* Encabezado Principal */
    .dashboard-header { 
        background: #ffffff; 
        padding: 30px 20px; 
        border-radius: 24px; 
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05); 
        margin-bottom: 30px; 
        text-align: center; 
        border-bottom: 4px solid #a87e3b; 
    }
    .dashboard-header h1 { margin: 0 0 8px 0; color: #124416; font-size: 1.8rem; font-weight: 700; }
    .dashboard-header p { margin: 0; color: #5e7087; font-size: 0.95rem; }
    
    /* Stats Grid Responsivo */
    .uco-stats-grid { 
        display: grid; 
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); 
        gap: 20px; 
        margin-bottom: 30px; 
    }
    .uco-stat-card { 
        background: #ffffff; 
        border-radius: 24px; 
        padding: 20px; 
        display: flex; 
        align-items: center; 
        gap: 15px; 
        border: 1px solid #e5e7eb; 
    }
    .uco-stat-icon-wrapper { width: 45px; height: 45px; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .uco-stat-number { color: #124416; font-size: 1.5rem; font-weight: 700; }
    .uco-stat-label { color: #64748b; font-size: 0.8rem; font-weight: 600; }

    /* Contenedor Principal (Tabla y Calendario) */
    .dashboard-main-content { 
        display: grid; 
        grid-template-columns: 1fr 1fr; 
        gap: 30px; 
        align-items: start;
    }

    /* Estilo de Tabla Institucional */
    .table-wrapper { 
        background: #ffffff; 
        border-radius: 20px; 
        overflow-x: auto; 
        border: 1px solid #e5e7eb; 
        box-shadow: 0 4px 6px rgba(0,0,0,0.05); 
    }
    .table-uco { width: 100%; border-collapse: collapse; min-width: 400px; }
    .table-uco thead { background-color: #124416; color: #ffffff; }
    .table-uco th { padding: 16px 20px; text-align: left; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; }
    .table-uco td { padding: 16px 20px; border-bottom: 1px solid #f1f5f9; font-size: 0.95rem; color: #334155; }
    .table-uco tbody tr:hover { background-color: #f8fafc; }
    
    .btn-calcular {
        background-color: #AA7F31; color: white; padding: 6px 16px;
        border-radius: 20px; text-decoration: none; font-size: 0.8rem; font-weight: 600;
        display: inline-block;
        transition: background-color 0.2s;
    }
    .btn-calcular:hover { background-color: #8c6725; }

    /* Calendario Card */
    .calendar-card { 
        background: #ffffff; 
        border-radius: 30px; 
        padding: 25px; 
        border: 1px solid #e5e7eb; 
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        min-width: 0; 
    }
    .calendar-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        margin-bottom: 18px;
    }
    .calendar-legend {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 8px;
    }
    .legend-item {
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 0.8rem;
        color: white;
        font-weight: 700;
    }
    .legend-descanso { background-color: #124416; }
    .legend-festivo { background-color: #AA7F31; color: #091014; }
    .legend-institucional { background-color: #340C51};
    
    /* Adaptaciones de UI para FullCalendar */
    .fc .fc-toolbar-title { font-size: 1.1rem !important; color: #124416; font-weight: 700; }
    .fc .fc-button { padding: 4px 8px !important; font-size: 0.85rem !important; }
    .fc .fc-col-header-cell-cushion, .fc .fc-daygrid-day-number { font-size: 0.85rem !important; }
    .fc .fc-event-title { font-size: 0.75rem !important; }

    
    @media (max-width: 1024px) {
        .dashboard-main-content {
            grid-template-columns: 1fr; 
            gap: 24px;
        }
        .dashboard-header { padding: 24px 15px; }
        .dashboard-header h1 { font-size: 1.6rem; }
    }

    @media (max-width: 640px) {
        .dashboard-container { padding: 10px; }
        .uco-stats-grid {
            grid-template-columns: 1fr 1fr; 
            gap: 12px;
        }
        .uco-stat-card { padding: 15px 12px; border-radius: 16px; gap: 10px; }
        .uco-stat-icon-wrapper { width: 38px; height: 38px; border-radius: 10px; }
        .uco-stat-icon-wrapper i { font-size: 0.9rem; }
        .uco-stat-number { font-size: 1.2rem; }
        .uco-stat-label { font-size: 0.75rem; }
        
        .calendar-card { padding: 15px; border-radius: 20px; }
        .fc .fc-toolbar {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
    }

    @media (max-width: 400px) {
        .uco-stats-grid {
            grid-template-columns: 1fr; 
        }
        .table-uco th, .table-uco td { padding: 12px 10px; font-size: 0.85rem; }
    }
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/dashboard.blade.php ENDPATH**/ ?>