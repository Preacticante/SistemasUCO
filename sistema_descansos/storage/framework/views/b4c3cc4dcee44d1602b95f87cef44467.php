<?php $__env->startSection('title', 'Configuración'); ?>
<?php $__env->startSection('header', 'Configuración del Sistema'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .config-container {
        font-family: 'Inter', sans-serif;
    }

    .config-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
        gap: 25px;
        margin-bottom: 30px;
    }
    
    /* Tarjetas con la estética institucional UCO */
    .config-card {
        background: white;
        padding: 0;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.04);
        border-bottom: 5px solid #AA7F31; /* Detalle Dorado UCO */
        overflow: hidden;
    }

    /* Barra de títulos en Verde Pino */
    .config-card h3 {
        margin: 0;
        background-color: #124416; 
        color: white;
        font-size: 0.95rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 18px 25px;
        border-top-left-radius: 16px;
        border-top-right-radius: 16px;
    }

    .config-card h3 i {
        color: white;
        opacity: 0.9;
    }

    /* Filas de control interno */
    .config-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 0;
        padding: 20px 25px;
        border-bottom: 1px solid #f1f5f9; 
    }

    .config-row:last-child {
        border-bottom: none;
    }

    .config-info h4 {
        margin: 0 0 5px 0;
        color: #1e293b;
        font-size: 0.95rem;
        font-weight: 600;
    }

    .config-info p {
        margin: 0;
        color: #64748b;
        font-size: 0.85rem;
    }

    /* Selects e Inputs de configuración */
    .config-select, .config-input-num {
        padding: 8px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        background-color: #f8fafc;
        color: #334155;
        font-size: 0.9rem;
        outline: none;
        cursor: pointer;
        transition: 0.2s;
    }
    .config-select:focus, .config-input-num:focus {
        border-color: #124416;
        background-color: white;
    }
    .config-input-num {
        width: 70px;
        text-align: center;
    }

    /* Interruptores (Toggles) de color institucional */
    .switch {
        position: relative;
        display: inline-block;
        width: 46px;
        height: 24px;
    }
    .switch input { 
        opacity: 0; width: 0; height: 0;
    }
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: #cbd5e1;
        transition: .3s;
        border-radius: 24px;
    }
    .slider:before {
        position: absolute;
        content: "";
        height: 18px; width: 18px;
        left: 3px; bottom: 3px;
        background-color: white;
        transition: .3s;
        border-radius: 50%;
    }
    input:checked + .slider {
        background-color: #124416; 
    }
    input:checked + .slider:before {
        transform: translateX(22px);
    }

    /* Botones Secundarios */
    .btn-config {
        background-color: #f8fafc;
        color: #124416;
        border: 1px solid #cbd5e1;
        padding: 10px 18px;
        border-radius: 20px;
        cursor: pointer;
        font-weight: 600;
        font-size: 0.85rem;
        transition: 0.2s;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .btn-config:hover {
        background-color: #f1f5f9;
        border-color: #124416;
    }

    /* Botón Principal de Guardar Cambios */
    .btn-save-all {
        background-color: #124416;
        color: white;
        border: none;
        padding: 14px 35px;
        border-radius: 25px;
        font-size: 1rem;
        font-weight: 700;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(18, 68, 22, 0.2);
        transition: 0.2s;
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 10px 0 0 auto;
    }
    .btn-save-all:hover {
        background-color: #0d3310;
        transform: translateY(-1px);
    }

    /* Tablas internas de los Modales */
    .modal-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 0;
        font-size: 0.9rem;
    }
    .modal-table th {
        background-color: #f1f5f9;
        color: #475569;
        text-align: left;
        padding: 12px 10px;
        font-weight: 700;
    }
    .modal-table td {
        padding: 12px 10px;
        border-bottom: 1px solid #e2e8f0;
        color: #334155;
    }

    /* --- RESPONSIVO --- */
    @media (max-width: 768px) {
        .config-grid { grid-template-columns: 1fr; }
        .config-row { flex-direction: column; align-items: flex-start; gap: 12px; }
        .switch { align-self: flex-end; margin-top: -25px; }
        .btn-save-all { width: 100%; justify-content: center; }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="config-container">

    <!-- ALERTAS DE ÉXITO OPERATIVO -->
    <?php if(session('success')): ?>
        <div style="background-color: #d1e7dd; color: #0f5132; padding: 15px 20px; border-radius: 12px; margin-bottom: 25px; border: 1px solid #badbcc; display: flex; align-items: center; gap: 10px; font-weight: 500;">
            <i class="fa-solid fa-circle-check" style="font-size: 1.2rem;"></i>
            <span><?php echo e(session('success')); ?></span>
        </div>
    <?php endif; ?>

    <!-- FORMULARIO CONECTADO CON WEB.PHP -->
    <form action="<?php echo e(route('configuracion.update')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        
        <div class="config-grid">
            
            <!-- TARJETA 1: OPERACIÓN DE LA JORNADA -->
            <div class="config-card">
                <h3><i class="fa-solid fa-business-time"></i> Reglas de Jornada Laboral</h3>
                
                <div class="config-row">
                    <div class="config-info">
                        <h4>Contabilizar Sábados</h4>
                        <p>¿Los sábados computan como días de vacaciones disfrutados?</p>
                    </div>
                    <input type="hidden" name="sabados_contables" value="0">
                    <label class="switch">
                        <input type="checkbox" name="sabados_contables" value="1" <?php echo e(old('sabados_contables', session('sabados_contables')) == '1' ? 'checked' : ''); ?>>
                        <span class="slider"></span>
                    </label>
                    <?php $__errorArgs = ['sabados_contables'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div style="color:#b91c1c; font-size:0.85rem; margin-top:6px;"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="config-row">
                    <div class="config-info">
                        <h4>Periodo Continuo Obligatorio</h4>
                        <p>Días mínimos que debe tomar un empleado por solicitud legal.</p>
                    </div>
                    <select name="minimo_dias_continuos" class="config-select">
                        <option value="6" <?php echo e(old('minimo_dias_continuos', session('minimo_dias_continuos')) == '6' ? 'selected' : ''); ?>>6 Días continuos (Ley Federal)</option>
                        <option value="1" <?php echo e(old('minimo_dias_continuos', session('minimo_dias_continuos')) == '1' ? 'selected' : ''); ?>>Sin mínimo obligatorio</option>
                        <option value="2" <?php echo e(old('minimo_dias_continuos', session('minimo_dias_continuos')) == '2' ? 'selected' : ''); ?>>2 Días mínimos</option>
                    </select>
                    <?php $__errorArgs = ['minimo_dias_continuos'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div style="color:#b91c1c; font-size:0.85rem; margin-top:6px;"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <!-- TARJETA 2: CADUCIDAD Y AUDITORÍA (MÉTODO FIFO) -->
            <div class="config-card">
                <h3><i class="fa-solid fa-clock-rotate-left"></i> Caducidad y Regla FIFO</h3>
                
                <div class="config-row">
                    <div class="config-info">
                        <h4>Vencimiento de Bolsas de Días</h4>
                        <p>Meses de límite para gastar las vacaciones de un aniversario.</p>
                    </div>
                    <div>
                        <input type="number" name="meses_caducidad" value="<?php echo e(old('meses_caducidad', session('meses_caducidad', 18))); ?>" min="1" max="48" class="config-input-num">
                        <span style="font-size: 0.85rem; color:#64748b; font-weight:600; margin-left:5px;">Meses</span>
                        <?php $__errorArgs = ['meses_caducidad'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div style="color:#b91c1c; font-size:0.85rem; margin-top:6px;"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="config-row">
                    <div class="config-info">
                        <h4>Estrategia FIFO Automatizada</h4>
                        <p>Gasta obligatoriamente los saldos más antiguos primero.</p>
                    </div>
                    <span style="color:#124416; background-color:#e6f4ea; padding:4px 10px; border-radius:12px; font-size:0.75rem; font-weight:700; border:1px solid #badbcc;">✔ ACTIVO FIJO</span>
                </div>
            </div>

            <!-- TARJETA 3: CONTROL DE CICLOS Y POLÍTICAS -->
            <div class="config-card">
                <h3><i class="fa-solid fa-scale-balanced"></i> Políticas de la Institución</h3>
                
                <div class="config-row">
                    <div class="config-info">
                        <h4>Ciclo de Cómputo Activo</h4>
                        <p>Año calendario sobre el que operan los paneles del sistema.</p>
                    </div>
                    <select name="ciclo_actual" class="config-select">
                        <option value="2026" <?php echo e(old('ciclo_actual', session('ciclo_actual')) == '2026' ? 'selected' : ''); ?>>2026 (Ciclo Actual)</option>
                        <option value="2025" <?php echo e(old('ciclo_actual', session('ciclo_actual')) == '2025' ? 'selected' : ''); ?>>2025</option>
                        <option value="2024" <?php echo e(old('ciclo_actual', session('ciclo_actual')) == '2024' ? 'selected' : ''); ?>>2024</option>
                    </select>
                    <?php $__errorArgs = ['ciclo_actual'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div style="color:#b91c1c; font-size:0.85rem; margin-top:6px;"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="config-row">
                    <div class="config-info">
                        <h4>Tabla de Derecho por Ley</h4>
                        <p>Visualizar o reajustar los días correspondientes por año.</p>
                    </div>
                    <button type="button" onclick="abrirModalLey()" class="btn-config">
                        <i class="fa-solid fa-table-list"></i> Ajustar Tabla
                    </button>
                </div>

                <div class="config-row">
                    <div class="config-info">
                        <h4>Calendario de Días Festivos</h4>
                        <p>Establecer los días no laborables oficiales de la UCO.</p>
                    </div>
                    <button type="button" onclick="abrirModalFestivos()" class="btn-config">
                        <i class="fa-solid fa-calendar-day"></i> Días Festivos
                    </button>
                </div>
            </div>

        </div>

        <!-- BOTÓN DE ENVÍO -->
        <button type="submit" class="btn-save-all">
            <i class="fa-solid fa-floppy-disk"></i> Guardar Cambios Operativos
        </button>
    </form>
</div>

<!-- MODAL 1: TABLA DE LEY DE VACACIONES (CON SCROLL VIEW OPTIMIZADO) -->
<div id="modalLey" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 2000; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
    <div style="background: white; width: 100%; max-width: 500px; padding: 30px; border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); margin: 20px;">
        <h3 style="margin-top: 0; color: #124416; border-bottom: 2px solid #AA7F31; padding-bottom: 10px; margin-bottom: 15px; font-size: 1.3rem; background: transparent; padding-left: 0; border-radius:0;">
            <i class="fa-solid fa-table-list"></i> Ley de Vacaciones Actual
        </h3>
        <p style="color:#64748b; font-size:0.85rem; margin:0 0 15px 0;">Días correspondientes según el artículo 76 de la LFT.</p>
        
        <!-- CONTENEDOR CONTROLABILE CON SCROLL VIEW -->
        <div style="max-height: 250px; overflow-y: auto; padding-right: 2px; border: 1px solid #cbd5e1; border-radius: 8px;">
            <table class="modal-table">
                <thead style="position: sticky; top: 0; background-color: #f1f5f9; z-index: 10; box-shadow: 0 1px 0 #cbd5e1;">
                    <tr>
                        <th>Antigüedad</th>
                        <th>Días por Ley</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $leyVacaciones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ley): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($ley->anios_antiguedad); ?> <?php echo e($ley->anios_antiguedad == 1 ? 'Año' : 'Años'); ?></td>
                            <td><strong><?php echo e($ley->dias_derecho); ?> Días</strong></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="2" style="text-align: center; color: #64748b; padding: 20px;">No hay registros cargados en la base de datos.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div style="display: flex; justify-content: flex-end; margin-top: 20px;">
            <button type="button" onclick="cerrarModalLey()" style="background: #124416; color: white; border: none; padding: 10px 25px; border-radius: 20px; font-weight: 600; cursor: pointer;">Entendido</button>
        </div>
    </div>
</div>

<!-- MODAL 2: CALENDARIO DE FESTIVOS UCO -->
<div id="modalFestivos" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 2000; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
    <div style="background: white; width: 100%; max-width: 500px; padding: 30px; border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); margin: 20px;">
        <h3 style="margin-top: 0; color: #124416; border-bottom: 2px solid #AA7F31; padding-bottom: 10px; margin-bottom: 15px; font-size: 1.3rem; background: transparent; padding-left: 0; border-radius:0;">
            <i class="fa-solid fa-calendar-day"></i> Días Festivos No Laborables
        </h3>
        <p style="color:#64748b; font-size:0.85rem; margin:0; padding-bottom: 15px;">Días inhábiles oficiales bloqueados para el cálculo.</p>
        
        <table class="modal-table">
            <thead style="background-color: #f1f5f9;">
                <tr>
                    <th>Festividad</th>
                    <th>Fecha Inhábil</th>
                </tr>
            </thead>
            <tbody>
                <tr><td>Año Nuevo</td><td>01 de Enero</td></tr>
                <tr><td>Aniversario Constitución</td><td>05 de Febrero</td></tr>
                <tr><td>Natalicio de Benito Juárez</td><td>21 de Marzo</td></tr>
                <tr><td>Día del Trabajo</td><td>01 de Mayo</td></tr>
            </tbody>
        </table>
        
        <div style="display: flex; justify-content: flex-end; margin-top: 20px;">
            <button type="button" onclick="cerrarModalFestivos()" style="background: #124416; color: white; border: none; padding: 10px 25px; border-radius: 20px; font-weight: 600; cursor: pointer;">Cerrar Calendario</button>
        </div>
    </div>
</div>

<!-- CONTROL JAVASCRIPT -->
<script>
    function abrirModalLey() { document.getElementById('modalLey').style.display = 'flex'; }
    function cerrarModalLey() { document.getElementById('modalLey').style.display = 'none'; }
    function abrirModalFestivos() { document.getElementById('modalFestivos').style.display = 'flex'; }
    function cerrarModalFestivos() { document.getElementById('modalFestivos').style.display = 'none'; }

    window.onclick = function(event) {
        let mLey = document.getElementById('modalLey');
        let mFestivos = document.getElementById('modalFestivos');
        if (event.target == mLey) cerrarModalLey();
        if (event.target == mFestivos) cerrarModalFestivos();
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/configuracion.blade.php ENDPATH**/ ?>