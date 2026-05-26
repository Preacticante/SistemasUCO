<?php $__env->startSection('title', 'Empleados'); ?>
<?php $__env->startSection('header', 'Directorio de Personal'); ?>

<?php $__env->startSection('content'); ?>
<div style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; border-bottom: 2px solid #f3f4f6; padding-bottom: 15px;">
        <h2 style="margin: 0; color: #1e293b;">Control de Empleados</h2>
        <button style="background-color: #10b981; color: white; border: none; padding: 10px 20px; border-radius: 6px; font-weight: 600; cursor: pointer;">
            <i class="fa-solid fa-user-plus"></i> Agregar Empleado
        </button>
    </div>

    <table style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr style="background-color: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                <th style="padding: 14px; color: #64748b;">ID</th>
                <th style="padding: 14px; color: #64748b;">Nombre Completo</th>
                <th style="padding: 14px; color: #64748b;">Fecha de Ingreso</th>
                <th style="padding: 14px; color: #64748b; text-align: center;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $empleados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr style="border-bottom: 1px solid #f1f5f9;">
                <td style="padding: 14px; font-weight: bold; color: #1e293b;"><?php echo e($emp->id); ?></td>
                <td style="padding: 14px; color: #334155;"><?php echo e($emp->nombre); ?> <?php echo e($emp->apellido_paterno); ?> <?php echo e($emp->apellido_materno); ?></td>
                <td style="padding: 14px; color: #64748b;"><?php echo e(\Carbon\Carbon::parse($emp->fecha_ingreso)->format('d/m/Y')); ?></td>
                <td style="padding: 14px; text-align: center;">
                    <a href="<?php echo e(route('empleados.vacaciones', $emp->id)); ?>" style="background-color: #3b82f6; color: white; text-decoration: none; padding: 6px 14px; border-radius: 4px; font-size: 0.9rem; font-weight: 500; margin-right: 5px;">
                        <i class="fa-solid fa-calendar"></i> Vacaciones
                    </a>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\becario.tie\Documents\GitHub\SistemasUCO\sistema_descansos\resources\views/empleados/index.blade.php ENDPATH**/ ?>