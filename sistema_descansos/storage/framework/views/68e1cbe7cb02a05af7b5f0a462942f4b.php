<?php $__env->startSection('title', 'Inicio'); ?>
<?php $__env->startSection('header', 'UCO • Control de Personal'); ?>

<?php $__env->startPush('styles'); ?>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .dashboard-container { font-family: 'Inter', sans-serif; max-width: 1400px; margin: 0 auto; padding: 20px; }
        
        /* Encabezado Principal */
        .dashboard-header { 
            background: #ffffff; padding: 30px; border-radius: 24px; 
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05); margin-bottom: 30px; 
            text-align: center; border-bottom: 4px solid #a87e3b; 
        }
        .dashboard-header h1 { margin: 0 0 8px 0; color: #124416; font-size: 1.8rem; font-weight: 700; }
        .dashboard-header p { margin: 0; color: #5e7087; font-size: 0.95rem; }
        
        /* Stats Grid */
        .uco-stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px; }
        .uco-stat-card { background: #ffffff; border-radius: 24px; padding: 20px; display: flex; align-items: center; gap: 15px; border: 1px solid #e5e7eb; }
        .uco-stat-icon-wrapper { width: 45px; height: 45px; border-radius: 12px; display: flex; align-items: center; justify-content: center; }
        .uco-stat-number { color: #124416; font-size: 1.5rem; font-weight: 700; }
        .uco-stat-label { color: #64748b; font-size: 0.8rem; font-weight: 600; }

        .dashboard-main-content { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; }

        /* Estilo de Tabla Institucional con Franja Verde */
        .table-wrapper { background: #ffffff; border-radius: 20px; overflow: hidden; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .table-uco { width: 100%; border-collapse: collapse; }
        .table-uco thead { background-color: #124416; color: #ffffff; }
        .table-uco th { padding: 16px 20px; text-align: left; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; }
        .table-uco td { padding: 16px 20px; border-bottom: 1px solid #f1f5f9; font-size: 0.95rem; color: #334155; }
        .table-uco tbody tr:hover { background-color: #f8fafc; }
        
        .btn-calcular {
            background-color: #AA7F31; color: white; padding: 6px 16px;
            border-radius: 20px; text-decoration: none; font-size: 0.8rem; font-weight: 600;
        }

        /* Calendario */
        .calendar-card { background: #ffffff; border-radius: 30px; padding: 25px; border: 1px solid #e5e7eb; }
        .fc .fc-toolbar-title { font-size: 1rem !important; color: #124416; font-weight: 700; }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-container">
    <div class="dashboard-header">
        <h1>Panel Principal</h1>
        <p>Control administrativo de personal UCO</p>
    </div>

    <div class="uco-stats-grid">
        <div class="uco-stat-card"><div class="uco-stat-icon-wrapper" style="background: rgba(52, 12, 81, 0.1); color: #340C51;"><i class="fa-solid fa-users"></i></div>
            <div class="uco-stat-info"><span class="uco-stat-label">Total empleados</span><br><strong class="uco-stat-number"><?php echo e($totalEmpleados ?? 0); ?></strong></div>
        </div>
        <div class="uco-stat-card"><div class="uco-stat-icon-wrapper" style="background: rgba(170, 127, 49, 0.1); color: #AA7F31;"><i class="fa-solid fa-book"></i></div>
            <div class="uco-stat-info"><span class="uco-stat-label">Derecho Total</span><br><strong class="uco-stat-number"><?php echo e($totalDiasDerecho ?? 0); ?></strong></div>
        </div>
        <div class="uco-stat-card"><div class="uco-stat-icon-wrapper" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;"><i class="fa-solid fa-calendar-xmark"></i></div>
            <div class="uco-stat-info"><span class="uco-stat-label">Tomados</span><br><strong class="uco-stat-number" style="color: #ef4444;"><?php echo e($diasTomadosEsteAnio ?? 0); ?></strong></div>
        </div>
        <div class="uco-stat-card"><div class="uco-stat-icon-wrapper" style="background: rgba(18, 68, 22, 0.1); color: #124416;"><i class="fa-solid fa-calendar-check"></i></div>
            <div class="uco-stat-info"><span class="uco-stat-label">Restantes</span><br><strong class="uco-stat-number" style="color: #124416;"><?php echo e($diasRestantesTotales ?? 0); ?></strong></div>
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
            <div id="calendar"></div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
      initialView: 'dayGridMonth',
      locale: 'es',
      dayMaxEvents: 2,
      headerToolbar: { left: 'prev,next', center: 'title', right: 'today' },
      events: '/api/eventos-vacaciones',
      height: 400
    });
    calendar.render();
  });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/dashboard.blade.php ENDPATH**/ ?>