<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte Ejecutivo de Vacaciones - <?php echo e($anioActual); ?></title>
    <style>
        body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; font-size: 12px; color:#222 }
        .header { text-align:center; margin-bottom:12px }
        table { width:100%; border-collapse: collapse; margin-bottom:10px }
        th, td { border:1px solid #ddd; padding:6px 8px; }
        th { background:#f3f3f3; font-weight:700 }
        .small { font-size:11px; color:#555 }
        .totals { font-weight:700 }
        .alerts { margin-top:10px }
    </style>
</head>
<body>
    <div class="header">
        <h2>Reporte Ejecutivo de Vacaciones - <?php echo e($anioActual); ?></h2>
        <div class="small">Generado: <?php echo e($fechaReporte ?? now()->format('d/m/Y H:i')); ?></div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Empleado</th>
                <th>Puesto</th>
                <th>Antigüedad (años)</th>
                <th>Días derecho</th>
                <th>Días tomados</th>
                <th>Días restantes</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $resumenPlantilla; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td style="width:40px"><?php echo e($i + 1); ?></td>
                    <td><?php echo e($r['nombre']); ?></td>
                    <td><?php echo e($r['puesto']); ?></td>
                    <td style="text-align:center"><?php echo e($r['antiguedad']); ?></td>
                    <td style="text-align:center"><?php echo e($r['derecho']); ?></td>
                    <td style="text-align:center"><?php echo e($r['tomados']); ?></td>
                    <td style="text-align:center"><?php echo e($r['restantes']); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <div class="alerts">
        <h4>Alertas (<= 2 días restantes)</h4>
        <?php if(count($listaAlertas) === 0): ?>
            <div class="small">No hay alertas.</div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Empleado</th>
                        <th>Puesto</th>
                        <th>Días restantes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $listaAlertas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($a['nombre']); ?></td>
                            <td><?php echo e($a['puesto']); ?></td>
                            <td style="text-align:center"><?php echo e($a['restantes']); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

</body>
</html><?php /**PATH /var/www/html/resources/views/reportes/ejecutivo.blade.php ENDPATH**/ ?>