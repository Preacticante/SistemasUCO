<div class="stat-card" style="margin-bottom: 24px;">
    <strong>Empleados con menos días restantes</strong>
    <table class="alert-table">
        <thead>
            <tr>
                <th>Empleado</th>
                <th>Días restantes</th>
                <th>Días tomados</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $empleadosConMenosDias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($item->empleado->nombre_completo); ?></td>
                    <td><?php echo e($item->diasRestantes); ?></td>
                    <td><?php echo e($item->diasTomados); ?></td>
                    <td><a href="<?php echo e(route('empleados.vacaciones', $item->empleado->id)); ?>" class="btn-calcular">Ver</a></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php /**PATH C:\Users\becario.tie\Documents\GitHub\SistemasUCO\sistema_descansos\resources\views/dashboard/alerts-table.blade.php ENDPATH**/ ?>