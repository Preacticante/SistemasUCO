<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Vacaciones - <?php echo e($anioActual); ?></title>
    <style>
        body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; font-size: 12px; }
        .title { text-align: center; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; }
        th { background: #f4f4f4; }
    </style>
</head>
<body>
    <div class="title">
        <h2>Reporte de Vacaciones - <?php echo e($anioActual); ?></h2>
        <p>Resumen de días tomados y días pendientes por empleado</p>
    </div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Empleado</th>
                <th>Puesto</th>
                <th>Fecha ingreso</th>
                <th>Días derecho</th>
                <th>Días tomados</th>
                <th>Días adeuda</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($i + 1); ?></td>
                    <td><?php echo e($r['empleado']); ?></td>
                    <td><?php echo e($r['puesto'] ?? '-'); ?></td>
                    <td><?php echo e($r['fecha_ingreso'] ?? '-'); ?></td>
                    <td style="text-align: center;"><?php echo e($r['dias_derecho']); ?></td>
                    <td style="text-align: center;"><?php echo e($r['dias_tomados']); ?></td>
                    <td style="text-align: center;"><?php echo e($r['dias_adeuda']); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</body>
</html><?php /**PATH /var/www/html/resources/views/pdfs/empleados_todos.blade.php ENDPATH**/ ?>