<?php $__env->startSection('title', 'Inicio'); ?>
<?php $__env->startSection('header', 'Dashboard General'); ?>

<?php $__env->startPush('styles'); ?>
    <?php echo $__env->make('dashboard.styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <?php
        $path = public_path('img/logo_uco.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    ?>

    <div class="container">
        <div style="margin-bottom: 30px;">
            <img src="<?php echo e($base64); ?>" 
                 alt="UCO PREPA CONTEMPORÁNEA"
                 style="height: 120px;"> 
        </div>

        <?php echo $__env->make('dashboard.stat-cards', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('dashboard.alerts-table', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('dashboard.employees-table', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\becario.tie\Documents\GitHub\SistemasUCO\sistema_descansos\resources\views/dashboard.blade.php ENDPATH**/ ?>