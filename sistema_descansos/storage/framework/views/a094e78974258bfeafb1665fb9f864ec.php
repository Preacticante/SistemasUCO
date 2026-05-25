<?php $__env->startSection('title', 'Historial'); ?>
<?php $__env->startSection('header', 'Historial de Vacaciones'); ?>

<?php $__env->startSection('content'); ?>
    <div style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
        <h2>Registro de Descansos</h2>
        <p>Aquí se muestra la bitácora de los periodos vacacionales registrados en la base de datos.</p>
    </div>

    <div style="overflow-x: auto; margin-top: 20px;">
        <table class="responsive-table">
            <thead>
                <tr>
                    <th>Empleado</th>
                    <th>Tipo</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Días Totales</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $periodosVacacionales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $periodo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $empleado = $periodo->empleado;
                        $fechaFin = \Carbon\Carbon::parse($periodo->fecha_fin);
                        $estado = $fechaFin->isPast() ? 'Tomado' : 'Programado';
                        $badgeClass = $fechaFin->isPast() ? 'badge-success' : 'badge-info';
                    ?>
                    <tr>
                        <td><?php echo e($empleado?->nombre); ?> <?php echo e($empleado?->apellido_paterno); ?> <?php echo e($empleado?->apellido_materno); ?></td>
                        <td>Vacaciones</td>
                        <td><?php echo e(\Carbon\Carbon::parse($periodo->fecha_inicio)->format('d/m/Y')); ?></td>
                        <td><?php echo e($fechaFin->format('d/m/Y')); ?></td>
                        <td><?php echo e($periodo->dias); ?> día<?php echo e($periodo->dias === 1 ? '' : 's'); ?></td>
                        <td><span class="badge <?php echo e($badgeClass); ?>"><?php echo e($estado); ?></span></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" style="text-align:center; padding: 18px 0; color: #475569;">No hay registros de vacaciones en la base de datos.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <style>
        .table-container {
            background: white; 
            padding: 30px; 
            border-radius: 12px; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .header-section {
            margin-bottom: 25px;
        }
        .header-section h2 {
            margin: 0 0 8px 0;
            color: #1f324f;
        }
        .header-section p {
            margin: 0;
            color: #64748b;
        }
        .responsive-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 0.95rem;
        }
        .responsive-table thead tr {
            background-color: #f8fafc;
            color: #1e293b;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #e2e8f0;
        }
        .responsive-table th, .responsive-table td {
            padding: 14px 16px;
        }
        .responsive-table tbody tr {
            border-bottom: 1px solid #f1f5f9;
            transition: background-color 0.2s;
        }
        .responsive-table tbody tr:hover {
            background-color: #f8fafc;
        }
        /* Estilos para las etiquetas de estado (Badge) */
        .badge {
            padding: 6px 12px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 500;
            display: inline-block;
        }
        .badge-success {
            background-color: #dcfce7;
            color: #15803d;
        }
        .badge-info {
            background-color: #e0f2fe;
            color: #0369a1;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\becario.tie\Documents\GitHub\SistemasUCO\sistema_descansos\resources\views/historial.blade.php ENDPATH**/ ?>