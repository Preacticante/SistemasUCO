<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Vacaciones - Resumen <?php echo e($anio); ?></title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #444; padding: 4px; text-align: left; }
        th { background: #eee; }
        .small { font-size: 11px }
    </style>
</head>
<body>
    <h2>Resumen de Vacaciones - Año <?php echo e($anio); ?></h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Puesto</th>
                <th>Fecha ingreso</th>
                <th>Antig.</th>
                <th>Días derecho</th>
                <?php $__currentLoopData = $meses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <th class="small"><?php echo e(substr($m,0,3)); ?></th>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <th>Total tomados</th>
                <th>Restantes</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($i+1); ?></td>
                    <td><?php echo e($r['empleado']->nombre); ?> <?php echo e($r['empleado']->apellido_paterno); ?> <?php echo e($r['empleado']->apellido_materno); ?></td>
                    <td><?php echo e($r['empleado']->puesto?->nombre ?? '-'); ?></td>
                    <td><?php echo e($r['empleado']->fecha_ingreso); ?></td>
                    <td style="text-align:center"><?php echo e($r['antiguedad']); ?></td>
                    <td style="text-align:center"><?php echo e($r['dias_derecho']); ?></td>
                    <?php $__currentLoopData = $r['registroPorMes']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <td style="text-align:center"><?php echo e($m); ?></td>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <td style="text-align:center"><?php echo e($r['diasTomados']); ?></td>
                    <td style="text-align:center"><?php echo e($r['diasRestantes']); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</body>
</html><?php /**PATH /var/www/html/resources/views/empleados/vacaciones_all_pdf.blade.php ENDPATH**/ ?>