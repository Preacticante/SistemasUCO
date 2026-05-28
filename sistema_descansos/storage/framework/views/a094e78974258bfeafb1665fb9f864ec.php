<?php $__env->startSection('title', 'Historial'); ?>
<?php $__env->startSection('header', 'Historial de Vacaciones'); ?>

<?php $__env->startSection('content'); ?>
    <div class="panel-principal-header">
        <h2>Registro de Descansos</h2>
        <p>Aquí se muestra la bitácora de los periodos vacacionales registrados en la base de datos.</p>
    </div>

    <div class="table-card-container">
        <div class="table-card-header">
            Historial de Vacaciones Registradas
        </div>
        <div style="overflow-x: auto;">
            <table class="responsive-table-v2">
                <thead>
                    <tr>
                        <th>EMPLEADO</th>
                        <th>TIPO</th>
                        <th>FECHA INICIO</th>
                        <th>FECHA FIN</th>
                        <th>DÍAS TOTALES</th>
                        <th>ESTADO</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $periodosVacacionales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $periodo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $empleado = $periodo->empleado;
                            $fechaFin = \Carbon\Carbon::parse($periodo->fecha_fin);
                            $estado = $fechaFin->isPast() ? 'Tomado' : 'Programado';
                            
                            // Mapeo de estilos según el estado de tu lógica original
                            $claseDias = $fechaFin->isPast() ? 'text-muted-days' : 'text-danger-bold';
                        ?>
                        <tr>
                            <td class="text-employee-name">
                                <?php echo e($empleado?->nombre); ?> <?php echo e($empleado?->apellido_paterno); ?> <?php echo e($empleado?->apellido_materno); ?>

                            </td>
                            <td style="color: #64748b; font-weight: 500;">
                                Vacaciones
                            </td>
                            <td style="color: #334155;">
                                <?php echo e(\Carbon\Carbon::parse($periodo->fecha_inicio)->format('d/m/Y')); ?>

                            </td>
                            <td style="color: #334155;">
                                <?php echo e($fechaFin->format('d/m/Y')); ?>

                            </td>
                            <td class="<?php echo e($claseDias); ?>">
                                <?php echo e($periodo->dias); ?> día<?php echo e($periodo->dias === 1 ? '' : 's'); ?>

                            </td>
                            <td>
                                <span class="badge <?php echo e($fechaFin->isPast() ? 'badge-success' : 'badge-info'); ?>">
                                    <?php echo e($estado); ?>

                                </span>
                            </td>
                            
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" style="text-align:center; padding: 25px 0; color: #5e7087;">
                                No hay registros de vacaciones en la base de datos.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

   <style>
        /* Estilo para la cabecera superior de la página */
        .panel-principal-header {
            background: white; 
            padding: 24px 30px; 
            border-radius: 20px 20px 16px 16px;
            box-shadow: 0 4px 10px rgba(240, 11, 42, 0.02);
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 4px solid #a87e3b;
        }
        .panel-principal-header h2 {
            margin: 0 0 8px 0;
            color: #2b0b4d;
            font-size: 1.8rem;
            font-weight: 700;
        }
        .panel-principal-header p {
            margin: 0;
            color: #101111;
            font-size: 1rem;
        }

        /* Contenedor principal de la tabla (Tarjeta con bordes redondeados) */
        .table-card-container {
            background: white;
            border-radius: 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            margin-top: 25px;
            border: 1px solid #f1f5f9;
        }

        /* Cabecera morada de la tabla */
        .table-card-header {
            background-color: #340C51;
            color: white;
            padding: 18px 24px;
            font-size: 1.1rem;
            font-weight: 700;
            letter-spacing: 0.3px;
        }

        /* Estilos generales de la tabla */
        .responsive-table-v2 {
            width: 100%;
            border-collapse: collapse;
            font-family: system-ui, -apple-system, sans-serif;
            font-size: 0.95rem;
        }

        /* Encabezados de columnas (TH) */
        .responsive-table-v2 thead tr {
            background-color: #ffffff;
            border-bottom: 1px solid #f1f5f9;
        }
        .responsive-table-v2 th {
            padding: 16px 24px;
            color: #334155;
            text-align: left;
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        /* Celdas del cuerpo (TD) */
        .responsive-table-v2 td {
            padding: 18px 24px;
            vertical-align: middle;
            border-bottom: 1px solid #f8fafc;
        }
        .responsive-table-v2 tbody tr:last-child td {
            border-bottom: none;
        }

        /* Tipografías de las columnas */
        .text-employee-name {
            color: #334155;
            font-weight: 600;
        }
        .text-danger-bold {
            color: #334155; /* Rojo para vacaciones programadas (resaltado) */
            font-weight: 700;
        }
        .text-muted-days {
            color: #334155; /* Gris para vacaciones ya tomadas */
            font-weight: 700;
        }


        /* Tus Badges de estado originales */
        .badge {
            padding: 6px 12px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 500;
            display: inline-block;
        }
        .badge-success {
            background-color: #124416;
            color: #f8faf8;
        }
        .badge-info {
            background-color: #AA7F31;
            color: #fdfcfc;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\becario.tie\Documents\GitHub\SistemasUCO\sistema_descansos\resources\views/historial.blade.php ENDPATH**/ ?>