<?php $__env->startSection('title', 'Inicio'); ?>
<?php $__env->startSection('header', 'UCO • Control de Personal'); ?>

<?php $__env->startPush('styles'); ?>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .dashboard-container {
            font-family: 'Inter', sans-serif;
            max-width: 1400px;
            margin: 0 auto;
            padding: 10px;
        }
        
        /* --- Encabezado Principal (Tarjeta Esmerilada) --- */
        .dashboard-header {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            padding: 25px 40px;
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.02);
            margin-bottom: 35px;
            text-align: center;
            border: 1px solid rgba(255,255,255,0.8);
            border-bottom: 4px solid #AA7F31; /* Detalle Dorado UCO */
        }
        
        .dashboard-header h1 {
            margin: 0;
            color: #340C51; /* MORADO INSTITUCIONAL */
            font-size: 1.8rem;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        
        .dashboard-header p {
            margin: 8px 0 0 0;
            color: #64748b;
            font-size: 1rem;
        }

        /* --- Grid Horizontal de Tarjetas de Métricas --- */
        .uco-stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 20px;
            margin-bottom: 35px;
            width: 100%;
        }

        .uco-stat-card {
            background: #ffffff;
            border-radius: 30px; /* Redondeado armónico para tarjetas chicas */
            padding: 22px 25px;
            display: flex;
            align-items: center;
            gap: 15px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 8px 20px rgba(52, 12, 81, 0.01);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .uco-stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(52, 12, 81, 0.04);
        }

        .uco-stat-icon-wrapper {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .uco-stat-info {
            display: flex;
            flex-direction: column;
        }

        .uco-stat-label {
            color: #64748b;
            font-size: 0.85rem;
            font-weight: 600;
            line-height: 1.3;
            margin-bottom: 2px;
        }

        .uco-stat-number {
            color: #340C51;
            font-size: 1.7rem;
            font-weight: 700;
            line-height: 1;
        }

        /* --- Contenedor de la Tabla con Redondeado de 50px --- */
        .table-uco-container {
            background: #ffffff;
            border-radius: 50px; /* REDONDEADO PERFECTO DE 50PX */
            overflow: hidden; 
            border: 1px solid #e5e7eb;
            box-shadow: 0 10px 30px rgba(52, 12, 81, 0.02);
            margin-top: 10px;
        }

        .table-uco-header {
            background-color: #340C51; /* MORADO INSTITUCIONAL */
            padding: 20px 40px;
        }

        .table-uco-header strong {
            display: block;
            color: #ffffff;
            font-size: 1.2rem;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .alert-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .alert-table th, .alert-table td {
            text-align: left;
            padding: 16px 40px;
            border-bottom: 1px solid #edf2f7;
        }

        .alert-table th {
            background: #f8fafc;
            color: #340C51;
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .alert-table tr:last-child td {
            border-bottom: none;
        }

        /* Botón de Acción Dorado UCO */
        .btn-calcular {
            background-color: #AA7F31;
            color: white;
            padding: 8px 22px;
            border-radius: 50px;
            text-decoration: none;
            font-size: 0.88rem;
            font-weight: 600;
            display: inline-block;
            transition: all 0.2s ease;
            box-shadow: 0 4px 10px rgba(170, 127, 49, 0.15);
        }

        .btn-calcular:hover {
            background-color: #8c6827;
            transform: scale(1.03);
        }

        /* --- RESPONSIVO --- */
        @media (max-width: 1200px) {
            .uco-stats-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 768px) {
            .uco-stats-grid { grid-template-columns: 1fr; }
            .alert-table th, .alert-table td { padding: 12px 20px; }
            .table-uco-header { padding: 15px 20px; }
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-container">
    
    <div class="dashboard-header">
        <h1>Panel Principal</h1>
        <p>Resumen general del estado de vacaciones y alertas de personal activo.</p>
    </div>

    <div class="uco-stats-grid">
        <div class="uco-stat-card">
            <div class="uco-stat-icon-wrapper" style="background-color: rgba(52, 12, 81, 0.1); color: #340C51;">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="uco-stat-info">
                <span class="uco-stat-label">Total empleados</span>
                <strong class="uco-stat-number"><?php echo e($totalEmpleados ?? 17); ?></strong>
            </div>
        </div>

        <div class="uco-stat-card">
            <div class="uco-stat-icon-wrapper" style="background-color: rgba(170, 127, 49, 0.1); color: #AA7F31;">
                <i class="fa-solid fa-book"></i>
            </div>
            <div class="uco-stat-info">
                <span class="uco-stat-label">Días derecho</span>
                <strong class="uco-stat-number"><?php echo e($totalDiasDerecho ?? 282); ?></strong>
            </div>
        </div>

        <div class="uco-stat-card">
            <div class="uco-stat-icon-wrapper" style="background-color: rgba(239, 68, 68, 0.1); color: #ef4444;">
                <i class="fa-solid fa-calendar-xmark"></i>
            </div>
            <div class="uco-stat-info">
                <span class="uco-stat-label">Tomados este año</span>
                <strong class="uco-stat-number" style="color: #ef4444;"><?php echo e($diasTomadosEsteAnio ?? 0); ?></strong>
            </div>
        </div>

        <div class="uco-stat-card">
            <div class="uco-stat-icon-wrapper" style="background-color: rgba(18, 68, 22, 0.1); color: #124416;">
                <i class="fa-solid fa-calendar-check"></i>
            </div>
            <div class="uco-stat-info">
                <span class="uco-stat-label">Restantes totales</span>
                <strong class="uco-stat-number" style="color: #124416;"><?php echo e($diasRestantesTotales ?? 282); ?></strong>
            </div>
        </div>
    </div>

    <div class="table-uco-container">
        <div class="table-uco-header">
            <strong>Empleados con menos días restantes</strong>
        </div>
        
        <div style="overflow-x: auto;">
            <table class="alert-table">
                <thead>
                    <tr>
                        <th>Empleado</th>
                        <th>Días restantes</th>
                        <th>Días tomados</th>
                        <th style="text-align: center;">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $empleadosConMenosDias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td style="font-weight: 500; color: #1e293b;">
                                <?php echo e($item->empleado->nombre); ?> <?php echo e($item->empleado->apellido_paterno); ?> <?php echo e($item->empleado->apellido_materno); ?>

                            </td>
                            <td style="font-weight: 700; color: #ef4444;"><?php echo e($item->diasRestantes); ?> días</td>
                            <td style="color: #64748b;"><?php echo e($item->diasTomados); ?> días</td>
                            <td style="text-align: center;">
                                <a href="<?php echo e(route('empleados.vacaciones', $item->empleado->id)); ?>" class="btn-calcular">Ver</a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\becario.tie\Documents\GitHub\SistemasUCO\sistema_descansos\resources\views/dashboard.blade.php ENDPATH**/ ?>