<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre Completo</th>
            <th>Fecha de Ingreso</th>
            <th>Acción</th>
        </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $empleados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $empleado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><?php echo e($empleado->id); ?></td>
            <td><?php echo e($empleado->nombre_completo); ?></td>
            <td><?php echo e($empleado->fecha_ingreso); ?></td>
            <td>
                <a href="<?php echo e(route('empleados.vacaciones', $empleado->id)); ?>" class="btn-calcular">Vacaciones</a>
            </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<?php /**PATH C:\Users\becario.tie\Documents\GitHub\SistemasUCO\sistema_descansos\resources\views/screens/dashboard/employees-table.blade.php ENDPATH**/ ?>