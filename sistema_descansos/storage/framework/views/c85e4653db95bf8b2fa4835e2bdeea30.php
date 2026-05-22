<?php $__env->startSection('title', 'Panel Principal'); ?>

<?php $__env->startPush('styles'); ?>
    <?php echo $__env->make('screens.dashboard.styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('screens.dashboard.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div style="padding-left: 40px; padding-top: 15px; position: absolute;">
        <img src="<?php echo e(asset('img/logo_uco2.png')); ?>" 
             alt="UCO PREPA CONTEMPORÁNEA"
             style="height: 200px;">
    </div>
    

    <div class="container">

        <h1>Panel Principal</h1>

        <?php echo $__env->make('screens.dashboard.stat-cards', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('screens.dashboard.alerts-table', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('screens.dashboard.employees-table', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>


        
        
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('screens.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\becario.tie\Documents\GitHub\SistemasUCO\sistema_descansos\resources\views/screens/dashboard.blade.php ENDPATH**/ ?>