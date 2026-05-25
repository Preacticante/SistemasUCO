<?php $__env->startSection('title', 'Panel Principal'); ?>

<?php $__env->startPush('styles'); ?>
    <?php echo $__env->make('dashboard.styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('dashboard.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php
        $path = public_path('img/logo_uco.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    ?>

    <div style="max-width: 1200px; margin: 0 auto; padding: 20px 15px; position: relative;">
        
        <div style="position: absolute; left: 150px; top: 55px;">
            <img src="<?php echo e($base64); ?>" 
                 alt="UCO PREPA CONTEMPORÁNEA"
                 style="height: 150px; width: auto;">
        </div>

    <div class="container" style="margin-top: 40px;">
        <h1>Panel Principal</h1>

        <?php echo $__env->make('dashboard.stat-cards', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('dashboard.alerts-table', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('dashboard.employees-table', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\becario.tie\Documents\GitHub\SistemasUCO\sistema_descansos\resources\views/dashboard.blade.php ENDPATH**/ ?>