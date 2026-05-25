<?php $__env->startSection('title', 'Inicio'); ?>
<?php $__env->startSection('header', 'Dashboard General'); ?>

<?php $__env->startPush('styles'); ?>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .dashboard-container {
            font-family: 'Inter', sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 10px;
        }
        
        /* Tarjeta superior centrada */
        .dashboard-header {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            margin-bottom: 35px;
            text-align: center;
        }
        
        .dashboard-header h1 {
            margin: 0;
            color: #1e293b;
            font-size: 1.8rem;
            font-weight: 700;
        }
        
        .dashboard-header p {
            margin: 8px 0 0 0;
            color: #64748b;
            font-size: 1rem;
        }

        .dashboard-section { margin-top: 35px; }
    </style>
    <?php echo $__env->make('dashboard.styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="dashboard-container">
        
        <div class="dashboard-header">
            <h1>Panel Principal</h1>
            <p>Resumen general del estado de vacaciones y personal activo.</p>
        </div>

        <div>
            <?php echo $__env->make('dashboard.stat-cards', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
        
        <div class="dashboard-section">
            <?php echo $__env->make('dashboard.alerts-table', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
        
        <div class="dashboard-section">
            <?php echo $__env->make('dashboard.employees-table', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>

    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\becario.tie\Documents\GitHub\SistemasUCO\sistema_descansos\resources\views/dashboard.blade.php ENDPATH**/ ?>