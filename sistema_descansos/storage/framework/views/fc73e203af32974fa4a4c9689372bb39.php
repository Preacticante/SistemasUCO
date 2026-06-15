<?php $__env->startSection('title', 'Restablecer contraseña'); ?>

<?php $__env->startSection('hideLayout'); ?>

<?php $__env->startSection('content'); ?>
    <div class="auth-reset-wrapper">
        <div class="card">
            <?php
                $path = public_path('img/logo_uco.png');
                $type = pathinfo($path, PATHINFO_EXTENSION);
                if(file_exists($path)){
                    $data = file_get_contents($path);
                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                    echo '<img src="'.$base64.'" alt="UCO PREPA CONTEMPORÁNEA" class="logo">';
                }
            ?>

            <h2>Restablecer contraseña</h2>
            <p class="subtitle">Elige una nueva contraseña para tu cuenta.</p>

        <?php if(session('status')): ?>
            <div class="alert alert-success"><?php echo e(session('status')); ?></div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <ul style="margin: 0; padding-left: 1.25rem;">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?php echo e(route('password.update')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="token" value="<?php echo e($token); ?>">
            <input type="hidden" name="email" value="<?php echo e($email ?? old('email')); ?>">

            <div class="form-group">
                <label for="password">Nueva contraseña</label>
                <input id="password" type="password" name="password" required placeholder="••••••••" autocomplete="new-password" class="<?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" autofocus>
                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="field-error"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirmar contraseña</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required placeholder="••••••••" autocomplete="new-password" class="<?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                <?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="field-error"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <button type="submit" class="btn-primary">Guardar nueva contraseña</button>
        </form>

        <p class="footer-link">¿Recordaste tu contraseña? <a href="<?php echo e(route('login')); ?>">Volver al inicio</a></p>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('styles'); ?>
<style>
        * {
            box-sizing: border-box;
        }

        .auth-reset-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem 0;
        }

        .card {
            width: min(480px, calc(100% - 2rem));
            <?php $__env->startPush('styles'); ?>
            <style>
                :root { font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; color: #1f324f; }
                * { box-sizing: border-box; }

                /* Reuse login styles for auth pages (centered card on green background) */
                .content-area { padding: 0 !important; }
                body, .content-area { background: radial-gradient(circle at top left, #1a5c1e 0%, #124416 50%, #082109 100%); }

                .auth-reset-wrapper { display:flex; justify-content:center; align-items:center; min-height:100vh; padding:2rem 0; }
                .card { background: rgba(255,255,255,0.97); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); padding: 40px 36px; border-radius: 14px; width:100%; max-width:420px; box-shadow: 0 12px 30px rgba(0,0,0,0.25); border:1px solid rgba(255,255,255,0.06); text-align:center; }

                .logo { height: 120px; margin-bottom: 6px; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.06)); }
                h2 { margin: 8px 0 6px; font-size: 1.4rem; font-weight: 800; color: #124416; }
                .subtitle { color:#64748b; font-size:0.9rem; margin-bottom:22px; line-height:1.5; }

                .form-group { margin-bottom: 18px; text-align: left; }
                .form-group label { display:block; font-size:0.85rem; font-weight:700; color:#475569; margin-bottom:8px; text-transform:uppercase; letter-spacing:0.5px; }
                input { width:100%; padding:12px 14px; border-radius:10px; border:1px solid #cbd5e1; background:#f8fafc; color:#1e293b; font-size:0.95rem; }
                input:focus { border-color:#AA7F31; box-shadow:0 0 0 6px rgba(170,127,49,0.06); outline:none; }

                .btn-primary, button[type="submit"] { width:100%; padding:12px; background:#AA7F31; color:#fff; border:none; border-radius:24px; font-weight:700; cursor:pointer; box-shadow:0 6px 14px rgba(170,127,49,0.22); }
                .btn-primary:hover, button[type="submit"]:hover { background:#8c6827; transform:translateY(-2px); }

                .alert { padding:0.9rem; border-radius:10px; margin-bottom:1rem; font-size:0.95rem; }
                .alert-success { background:#f0fdf4; color:#166534; border:1px solid #bbf7d0; }
                .alert-danger { background:#fff7f7; color:#9b1c1c; border:1px solid #fecaca; }
                .field-error { display:block; margin-top:0.4rem; color:#ef4444; font-size:0.85rem; }

                .footer-link { margin-top:14px; color:#124416; }
                .footer-link a { color:#124416; text-decoration:underline; }

                @media (max-width:640px) { .auth-reset-wrapper { align-items:flex-start; padding:1rem; min-height:auto; } .card{ padding:18px; border-radius:12px; } }
            </style>
            <?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/auth/reset-password.blade.php ENDPATH**/ ?>