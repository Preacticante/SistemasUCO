<div class="stat-card table-uco-container">
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
                        <td><?php echo e($item->empleado->nombre); ?> <?php echo e($item->empleado->apellido_paterno); ?> <?php echo e($item->empleado->apellido_materno); ?></td>
                        <td style="font-weight: 700; color: #ef4444;"><?php echo e($item->diasRestantes); ?></td>
                        <td><?php echo e($item->diasTomados); ?></td>
                        <td style="text-align: center;">
                            <a href="<?php echo e(route('empleados.vacaciones', $item->empleado->id)); ?>" class="btn-calcular">Ver</a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div><?php /**PATH C:\Users\becario.tie\Documents\GitHub\SistemasUCO\sistema_descansos\resources\views/dashboard/alerts-table.blade.php ENDPATH**/ ?>