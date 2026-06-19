<?php $__env->startSection('title', 'Días Especiales'); ?>
<?php $__env->startSection('header', 'Días Especiales'); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-container" style="max-width: 1400px; margin: 0 auto; padding: 20px; font-family: system-ui, -apple-system, sans-serif;">
    
    
    <div class="dashboard-header-card">
        <h1>Gestión de días especiales</h1>
        <p>Agrega y administra descansos semanales, días festivos y vacaciones institucionales con selección personalizada.</p>
    </div>

    
    <?php if(session('success')): ?>
        <div class="alert alert-success">
            <i class="fa-solid fa-circle-check"></i> <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger">
            <i class="fa-solid fa-circle-xmark"></i> <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger" style="flex-direction: column; align-items: flex-start; gap: 5px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-triangle-exclamation"></i> <span>Por favor revisa los errores del formulario.</span>
            </div>
            <ul style="margin-top: 8px; margin-left: 24px; padding: 0; font-size: 0.9rem; opacity: 0.9;">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="special-days-hero">
        <div class="special-day-card special-day-descanso">
            <strong>Descanso de trabajador</strong>
            <span>Color verde</span>
        </div>
        <div class="special-day-card special-day-festivo">
            <strong>Día festivo por ley</strong>
            <span>Color dorado</span>
        </div>
        <div class="special-day-card special-day-institucional">
            <strong>Vacaciones institucionales</strong>
            <span>Color morado</span>
        </div>
    </div>

    <div class="special-days-form-grid">
        <article class="special-form-card">
            <h2><i class="fa-solid fa-calendar-plus"></i> Registrar día especial</h2>
            <form action="<?php echo e(route('dias-especiales.store')); ?>" method="POST" class="special-form">
                <?php echo csrf_field(); ?>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label for="tipo">Tipo de día</label>
                        <select id="tipo" name="tipo" required>
                            <option value="">Selecciona un tipo</option>
                            <option value="descanso">Descanso de trabajador</option>
                            <option value="festivo">Día festivo</option>
                            <option value="institucional">Vacaciones institucionales</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="selection_mode">Modalidad de Selección</label>
                        <select id="selection_mode" name="selection_mode">
                            <option value="personalizado">Personalizado</option>
                            <option value="semana">Por semana</option>
                            <option value="mes">Por mes</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="titulo">Título </label>
                    <input id="titulo" type="text" name="titulo" placeholder="Ej: Descanso semanal" value="<?php echo e(old('titulo')); ?>" required>
                </div>

                <div class="form-grid-2" style="margin: 5px 0;">
                    <div class="form-group">
                        <label>Fecha Inicio</label>
                        <input id="fecha_inicio_display" class="readonly-input" type="text" readonly placeholder="dd/mm/yyyy">
                    </div>
                    
                    <div class="form-group">
                        <label>Fecha Fin</label>
                        <input id="fecha_fin_display" class="readonly-input" type="text" readonly placeholder="dd/mm/yyyy">
                    </div>
                </div>

                <div class="form-group">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2px;">
                        <label for="empleados">Asignar trabajadores</label>
                        
                        <div id="bulk-select-container" style="display: none; gap: 10px; font-size: 0.85rem;">
                            <button type="button" id="btn-select-all" style="background: none; border: none; color: #AA7F31; font-weight: 700; cursor: pointer; padding: 0;">
                                <i class="fa-solid fa-check-double"></i> Seleccionar todos
                            </button>
                            <span style="color: #cbd5e1;">|</span>
                            <button type="button" id="btn-deselect-all" style="background: none; border: none; color: #64748b; font-weight: 600; cursor: pointer; padding: 0;">
                                <i class="fa-solid fa-xmark"></i> Deseleccionar todos
                            </button>
                        </div>
                    </div>
                    <div class="select-wrapper">
                        <select id="empleados" name="empleados[]" multiple size="5">
                            <?php $__currentLoopData = $empleados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($emp->id); ?>"><?php echo e($emp->nombre); ?> <?php echo e($emp->apellido_paterno); ?> <?php echo e($emp->apellido_materno); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    
                </div>

                <input type="hidden" id="multiple_dates" name="multiple_dates" value="<?php echo e(old('multiple_dates')); ?>">
                <input type="hidden" id="fecha_inicio" name="fecha_inicio" value="<?php echo e(old('fecha_inicio')); ?>">
                <input type="hidden" id="fecha_fin" name="fecha_fin" value="<?php echo e(old('fecha_fin')); ?>">

                <div class="form-group">
                    <label for="observaciones">Observaciones internas</label>
                    <textarea id="observaciones" name="observaciones" placeholder="Añade detalles u observaciones de manera opcional..."><?php echo e(old('observaciones')); ?></textarea>
                </div>

                <div class="summary-pill">
                    <span>Días seleccionados: <strong id="selected-count" class="badge">0</strong></span>
                    <span>Modo activo: <strong id="selection-type-text">Personalizado</strong></span>
                </div>

                <button type="submit" class="btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i> Guardar día especial
                </button>
            </form>
        </article>

        <article class="special-calendar-card">
            <h2><i class="fa-solid fa-calendar-days"></i> Selección de fechas</h2>
            <div class="calendar-controls">
                <i class="fa-solid fa-circle-info"></i> Usa el calendario interactivo para marcar los días según el tipo seleccionado.
            </div>
            <div id="calendar-inline-container">
                <div id="calendar-inline"></div>
            </div>
        </article>
    </div>

    <div class="table-wrapper">
        <div class="table-header-box">
            <h2><i class="fa-solid fa-list-check"></i> Listado de días especiales registrados</h2>
        </div>
        <div class="table-responsive">
            <table class="table-uco">
                <thead>
                    <tr>
                        <th>Tipo de Día</th>
                        <th>Título</th>
                        <th>Fecha de Inicio</th>
                        <th>Fecha de Fin</th>
                        <th style="text-align: center; width: 120px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $diasEspeciales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dia): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><span class="event-pill event-<?php echo e($dia->tipo); ?>"><?php echo e(ucfirst($dia->tipo)); ?></span></td>
                        <td style="font-weight: 600; color: #1e293b;"><?php echo e($dia->titulo); ?></td>
                        <td><i class="fa-regular fa-calendar" style="color: #64748b; margin-right: 6px;"></i><?php echo e($dia->fecha_inicio->format('d/m/Y')); ?></td>
                        <td><i class="fa-regular fa-calendar" style="color: #64748b; margin-right: 6px;"></i><?php echo e($dia->fecha_fin->format('d/m/Y')); ?></td>
                        <td style="text-align: center;">
                            <form action="<?php echo e(route('dias-especiales.destroy', $dia->id)); ?>" method="POST" class="form-eliminar" style="display:inline;">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn-danger-outline" title="Eliminar registro">
                                    <i class="fa-solid fa-trash-can"></i> Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="empty-table-state">
                            <i class="fa-solid fa-calendar-xmark"></i>
                            <p>No hay días especiales registrados en la base de datos.</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.css">

<style>
    .dashboard-header-card {
        background: #ffffff;
        border-radius: 32px;
        padding: 24px 20px;
        text-align: center;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
        border-bottom: 4px solid #AA7F31;
        margin-bottom: 35px;
    }
    .dashboard-header-card h1 {
        color: #124416;
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0 0 6px 0;
        letter-spacing: -0.02em;
    }
    .dashboard-header-card p {
        color: #576b85;
        font-size: 0.95rem;
        margin: 0;
        font-weight: 500;
    }

    .alert {
        padding: 16px;
        margin-bottom: 24px;
        border-radius: 14px;
        font-weight: 600;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 12px;
        border: 1px solid transparent;
        animation: fadeIn 0.3s ease-in-out;
    }
    .alert-success { background-color: #f0fdf4; border-color: #bbf7d0; color: #166534; }
    .alert-danger { background-color: #fef2f2; border-color: #fecaca; color: #991b1b; }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-5px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .special-days-hero {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    .special-day-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        display: flex;
        flex-direction: column;
        gap: 6px;
        transition: transform 0.2s, box-shadow 0.2s;
        border: 1px solid #f1f5f9;
    }
    .special-day-descanso { border-left: 5px solid #124416; }
    .special-day-festivo { border-left: 5px solid #AA7F31; }
    .special-day-institucional { border-left: 5px solid #340C51; }
    .special-day-card strong { font-size: 1rem; color: #1e293b; font-weight: 700; }
    .special-day-card span { font-size: 0.85rem; color: #64748b; font-weight: 500; }

    .special-days-form-grid {
        display: grid;
        grid-template-columns: 1.1fr 0.9fr;
        gap: 30px;
        margin-bottom: 35px;
        align-items: start;
    }
    
    .special-form-card,
    .special-calendar-card {
        background: #ffffff;
        border-radius: 20px;
        padding: 30px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 10px -1px rgba(15, 23, 42, 0.03);
        box-sizing: border-box;
    }
    
    .special-form-card h2,
    .special-calendar-card h2 {
        margin-top: 0;
        margin-bottom: 24px;
        color: #0f172a;
        font-size: 1.25rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .special-form-card h2 i, .special-calendar-card h2 i { color: #124416; }
    
    .special-form { display: flex; flex-direction: column; gap: 20px; }
    .form-group { display: flex; flex-direction: column; gap: 8px; width: 100%; box-sizing: border-box; }
    
    .form-grid-2 { 
        display: grid; 
        grid-template-columns: 1fr 1fr; 
        gap: 20px; 
        width: 100%;
        box-sizing: border-box;
    }
    
    .special-form select,
    .special-form textarea,
    .special-form input {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid #cbd5e1;
        border-radius: 12px;
        background: #fff;
        font-size: 0.95rem;
        color: #0f172a;
        transition: border-color 0.2s, box-shadow 0.2s;
        outline: none;
        box-sizing: border-box;
    }
    .special-form input:focus,
    .special-form select:focus,
    .special-form textarea:focus {
        border-color: #124416;
        box-shadow: 0 0 0 4px rgba(18, 68, 22, 0.08);
    }
    .special-form textarea { min-height: 90px; resize: vertical; }
    .readonly-input { background-color: #f8fafc !important; color: #64748b !important; cursor: not-allowed; }

    .select-wrapper select[multiple] { padding: 8px; }
    .select-wrapper select[multiple] option { padding: 8px 12px; margin-bottom: 4px; border-radius: 8px; cursor: pointer; }
    .select-wrapper select[multiple] option:checked {
        background: rgba(18, 68, 22, 0.1) linear-gradient(0deg, rgba(18, 68, 22, 0.1) 0%, rgba(18, 68, 22, 0.1) 100%);
        color: #124416;
        font-weight: 600;
    }

    .summary-pill {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        padding: 14px 20px;
        border-radius: 12px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #475569;
        font-size: 0.9rem;
    }
    .badge { background: #124416; color: white !important; padding: 2px 10px; border-radius: 20px; font-size: 0.85rem;}

    .btn-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        border: none;
        border-radius: 12px;
        padding: 14px 24px;
        color: white;
        background-color: #124416;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: background-color 0.2s, transform 0.1s;
    }
    .btn-primary:hover { background-color: #0d3210; }
    .btn-primary:active { transform: scale(0.99); }

    .btn-danger-outline {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        border: 1px solid #fca5a5;
        border-radius: 8px;
        padding: 6px 14px;
        color: #dc2626;
        background-color: #fff;
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-danger-outline:hover { background-color: #fef2f2; border-color: #ef4444; }

    .table-wrapper {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 10px -1px rgba(15, 23, 42, 0.03);
        overflow: hidden;
    }
    .table-header-box { padding: 24px 30px; border-bottom: 1px solid #e2e8f0; background-color: #f8fafc; }
    .table-header-box h2 { margin: 0; color: #0f172a; font-size: 1.15rem; font-weight: 700; display: flex; align-items: center; gap: 8px; }
    .table-responsive { overflow-x: auto; }
    .table-uco { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.95rem; }
    .table-uco th { background-color: #fff; color: #64748b; font-weight: 600; padding: 14px 24px; border-bottom: 2px solid #e2e8f0; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; }
    .table-uco td { padding: 16px 24px; border-bottom: 1px solid #f1f5f9; color: #334155; }
    .table-uco tbody tr:hover { background: #f8fafc; }

    .event-pill { display: inline-flex; padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 700; text-transform: capitalize; }
    .event-descanal, .event-descanso { background: #dcfce7; color: #15803d; }
    .event-festivo { background: #fef3c7; color: #a16207; }
    .event-institucional { background: #f3e8ff; color: #6b21a8; }
    .empty-table-state { text-align: center; padding: 40px !important; color: #94a3b8; }
    .empty-table-state i { font-size: 2.5rem; margin-bottom: 12px; display: block; opacity: 0.7; }

    /* REESTRUCTURACIÓN DE DISEÑO EN FLATPICKR PARA RELLENAR TODO EL MARCO GRIS */
    #calendar-inline-container {
        display: block;
        background: #f8fafc;
        padding: 24px;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        width: 100%;
        box-sizing: border-box;
    }
    
    #calendar-inline, 
    .flatpickr-calendar.inline,
    .flatpickr-months,
    .flatpickr-innerContainer,
    .flatpickr-rContainer,
    .flatpickr-days,
    .dayContainer { 
        width: 100% !important;
        max-width: 100% !important;
        min-width: 100% !important;
        box-sizing: border-box !important;
        display: flex !important;
        flex-direction: column !important;
    }

    .flatpickr-weekdays, .flatpickr-weekdaycontainer {
        display: flex !important;
        width: 100% !important;
    }
    
    .flatpickr-weekday {
        flex: 1 !important;
        max-width: 100% !important;
    }

    .dayContainer {
        flex-direction: row !important;
        flex-wrap: wrap !important;
    }

    /* Cada número se estira proporcionalmente al 100% de la caja disponible */
    .flatpickr-day { 
        flex: 1 0 14.28% !important;
        max-width: 14.28% !important;
        height: auto !important;
        aspect-ratio: 1 / 1 !important;
        margin: 0 !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        border-radius: 8px !important;
        box-sizing: border-box !important;
        font-size: 1.05rem !important;
    }

    .calendar-controls { margin-bottom: 16px; color: #64748b; font-size: 0.9rem; display: flex; align-items: center; gap: 8px;}
    .flatpickr-calendar.inline { box-shadow: none !important; background: transparent; }
    
    .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange {
        background: var(--selected-day-bg, #124416) !important;
        color: var(--selected-day-color, #ffffff) !important;
        border-color: var(--selected-day-bg, #124416) !important;
    }
    .flatpickr-day.today { color: #124416; font-weight: 700; border-color: transparent !important; background: rgba(18, 68, 22, 0.08); }

    @media (max-width: 1080px) {
        .special-days-form-grid { grid-template-columns: 1fr; }
    }

    .flatpickr-day.bloqueado-descanso, .flatpickr-day.bloqueado-descanso:hover { background: #124416 !important; color: #ffffff !important; border-color: #124416 !important; opacity: 1 !important; cursor: not-allowed; }
    .flatpickr-day.bloqueado-festivo, .flatpickr-day.bloqueado-festivo:hover { background: #AA7F31 !important; color: #ffffff !important; border-color: #AA7F31 !important; opacity: 1 !important; cursor: not-allowed; }
    .flatpickr-day.bloqueado-institucional, .flatpickr-day.bloqueado-institucional:hover { background: #340C51 !important; color: #ffffff !important; border-color: #340C51 !important; opacity: 1 !important; cursor: not-allowed; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const tipoSelect = document.getElementById('tipo');
    const selectionMode = document.getElementById('selection_mode');
    const selectionText = document.getElementById('selection-type-text');
    const selectedCount = document.getElementById('selected-count');
    const inputHiddenDates = document.getElementById('multiple_dates');
    const inputFechaInicio = document.getElementById('fecha_inicio');
    const inputFechaFin = document.getElementById('fecha_fin');
    const inputFechaInicioDisplay = document.getElementById('fecha_inicio_display');
    const inputFechaFinDisplay = document.getElementById('fecha_fin_display');
    const selectEmpleados = document.getElementById('empleados');
    const bulkSelectContainer = document.getElementById('bulk-select-container');
    const btnSelectAll = document.getElementById('btn-select-all');
    const btnDeselectAll = document.getElementById('btn-deselect-all');

    function toggleBulkSelect() {
        if (tipoSelect.value === 'festivo') {
            bulkSelectContainer.style.display = 'flex';
        } else {
            bulkSelectContainer.style.display = 'none';
        }
    }

    // Acción: Seleccionar todos
    btnSelectAll.addEventListener('click', function() {
        for (let i = 0; i < selectEmpleados.options.length; i++) {
            selectEmpleados.options[i].selected = true;
        }
    });

    // Acción: Deseleccionar todos
    btnDeselectAll.addEventListener('click', function() {
        for (let i = 0; i < selectEmpleados.options.length; i++) {
            selectEmpleados.options[i].selected = false;
        }
    });

    const colors = { descanso: '#124416', festivo: '#AA7F31', institucional: '#340C51' };
    const textColors = { descanso: '#ffffff', festivo: '#ffffff', institucional: '#ffffff' };

    function updateSelectedColor() {
        const type = tipoSelect.value || 'descanso';
        document.documentElement.style.setProperty('--selected-day-bg', colors[type]);
        document.documentElement.style.setProperty('--selected-day-color', textColors[type]);
        
        toggleBulkSelect();
        
        if (fp) {
            if (type === 'descanso') { fp.set('disable', disabledForDescanso); } else { fp.set('disable', []); }
            fp.redraw();
            colorearDiasBloqueados();
        }
    }

    function colorearDiasBloqueados() {
        if (!fp) return;
        const dayElements = fp.calendarContainer.querySelectorAll('.flatpickr-day');
        dayElements.forEach(dayElement => {
            const ds = dayElement.dateObj ? fp.formatDate(dayElement.dateObj, 'Y-m-d') : null;
            if (ds && specialDateMap[ds]) {
                const tipoDiaHistorial = specialDateMap[ds].tipo;
                dayElement.classList.remove('selected', 'startRange', 'endRange');
                if (tipoDiaHistorial === 'descanso') dayElement.classList.add('bloqueado-descanso');
                if (tipoDiaHistorial === 'festivo') dayElement.classList.add('bloqueado-festivo');
                if (tipoDiaHistorial === 'institucional') dayElement.classList.add('bloqueado-institucional');
            }
        });
    }

    function formatDateDisplay(date) {
        if (!date) return '';
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        return `${day}/${month}/${year}`;
    }

    function updateFields(selectedDates) {
        const sorted = selectedDates.slice().sort((a, b) => a - b);
        const dateStrings = sorted.map(date => fp.formatDate(date, 'Y-m-d'));
        inputHiddenDates.value = dateStrings.join(',');
        inputFechaInicio.value = dateStrings.length ? dateStrings[0] : '';
        inputFechaFin.value = dateStrings.length ? dateStrings[dateStrings.length - 1] : '';
        inputFechaInicioDisplay.value = sorted.length ? formatDateDisplay(sorted[0]) : '';
        inputFechaFinDisplay.value = sorted.length ? formatDateDisplay(sorted[sorted.length - 1]) : '';
        selectedCount.textContent = dateStrings.length;
    }

    function getWeekRange(date) {
        const dayIndex = date.getDay();
        const monday = new Date(date);
        monday.setDate(date.getDate() - ((dayIndex + 6) % 7));
        const sunday = new Date(monday);
        sunday.setDate(monday.getDate() + 6);
        return [monday, sunday];
    }

    function getMonthRange(date) {
        const firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
        const lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);
        return [firstDay, lastDay];
    }

    function getDatesBetween(start, end) {
        const dates = [];
        const current = new Date(start.getFullYear(), start.getMonth(), start.getDate());
        const final = new Date(end.getFullYear(), end.getMonth(), end.getDate());
        while (current <= final) {
            dates.push(new Date(current));
            current.setDate(current.getDate() + 1);
        }
        return dates;
    }

    function parseYMDToLocal(dateStr) {
        if (!dateStr) return null;
        const cleanStr = String(dateStr).split('T')[0];
        const parts = cleanStr.split('-').map(p => parseInt(p, 10));
        if (parts.length === 3) return new Date(parts[0], parts[1] - 1, parts[2]);
        return new Date(cleanStr);
    }

    function formatYMD(date) {
        if (!date) return '';
        const y = date.getFullYear();
        const m = String(date.getMonth() + 1).padStart(2, '0');
        const d = String(date.getDate()).padStart(2, '0');
        return `${y}-${m}-${d}`;
    }

    // Vincula rangos completos de fechas según la selección
    function selectRange(start, end) {
        const dates = getDatesBetween(start, end);
        fp.setDate(dates, true, 'Y-m-d');
        updateFields(dates);
    }

    function clearSelection() { fp.clear(); updateFields([]); }

    let fp;

    function createSpecialDayCalendar() {
        fp = flatpickr('#calendar-inline', {
            inline: true,
            mode: 'multiple',
            locale: 'es',
            dateFormat: 'Y-m-d',
            defaultDate: inputHiddenDates.value ? inputHiddenDates.value.split(',') : [],
            onDayCreate: function(dObj, dStr, dayElement) {
                const ds = dayElement.dateObj ? fp.formatDate(dayElement.dateObj, 'Y-m-d') : null;
                if (ds && specialDateMap[ds]) {
                    const tipoDiaHistorial = specialDateMap[ds].tipo;
                    dayElement.classList.remove('selected', 'startRange', 'endRange');
                    if (tipoDiaHistorial === 'descanso') dayElement.classList.add('bloqueado-descanso');
                    if (tipoDiaHistorial === 'festivo') dayElement.classList.add('bloqueado-festivo');
                    if (tipoDiaHistorial === 'institucional') dayElement.classList.add('bloqueado-institucional');
                }

                dayElement.dateObj && dayElement.addEventListener('click', function(e) {
                    const mode = selectionMode.value;
                    const type = tipoSelect.value;
                    if (type === 'descanso' && disabledForDescansoSet.has(fp.formatDate(dayElement.dateObj, 'Y-m-d'))) {
                        e.preventDefault();
                        Swal.fire({ icon: 'warning', title: 'Fecha bloqueada', text: 'Esa fecha está marcada como festivo o vacaciones institucionales.' });
                        return;
                    }

                    if (mode === 'semana') {
                        const [start, end] = getWeekRange(dayElement.dateObj);
                        const dates = getDatesBetween(start, end).map(d => fp.formatDate(d, 'Y-m-d'));
                        if (type === 'descanso' && dates.some(d => disabledForDescansoSet.has(d))) {
                            Swal.fire({ icon: 'error', title: 'Conflicto de fechas', text: 'La semana contiene días festivos o institucionales.' });
                            return;
                        }
                        selectRange(start, end);
                    } else if (mode === 'mes') {
                        const [start, end] = getMonthRange(dayElement.dateObj);
                        const dates = getDatesBetween(start, end).map(d => fp.formatDate(d, 'Y-m-d'));
                        if (type === 'descanso' && dates.some(d => disabledForDescansoSet.has(d))) {
                            Swal.fire({ icon: 'error', title: 'Conflicto de fechas', text: 'El mes contiene días festivos o institucionales.' });
                            return;
                        }
                        selectRange(start, end);
                    }
                });
            },
            onMonthChange: function() { setTimeout(colorearDiasBloqueados, 10); },
            onYearChange: function() { setTimeout(colorearDiasBloqueados, 10); },
            onChange: function(selectedDates) {
                const mode = selectionMode.value;
                if (mode === 'varios' || mode === 'personalizado') updateFields(selectedDates);
                if (selectedDates.length === 0) updateFields([]);
                fp.redraw();
                colorearDiasBloqueados();
            }
        });
        colorearDiasBloqueados();
    }

    const specialDateMap = {};
    const disabledForDescanso = [];
    const disabledForDescansoSet = new Set();
    
    fetch('/api/eventos-vacaciones').then(r => r.json()).then(events => {
        events.forEach(ev => {
            if (!ev.extendedProps || !ev.extendedProps.is_special) return;
            const start = parseYMDToLocal(ev.start);
            let end = ev.end ? parseYMDToLocal(ev.end) : start;
            if (ev.end && ev.start !== ev.end) end.setDate(end.getDate() - 1);
            const dates = getDatesBetween(start, end).map(d => formatYMD(d));
            
            dates.forEach(d => {
                const tipo = ev.extendedProps.tipo;
                specialDateMap[d] = { tipo: tipo };
                if (tipo === 'festivo' || tipo === 'institucional') {
                    if (!disabledForDescansoSet.has(d)) {
                        disabledForDescanso.push(d);
                        disabledForDescansoSet.add(d);
                    }
                }
            });
        });
        createSpecialDayCalendar();
        updateSelectedColor();
    }).catch((err) => { 
        console.error("Error al mapear días especiales:", err);
        createSpecialDayCalendar(); 
    });

    selectionMode.addEventListener('change', function() {
        selectionText.textContent = this.options[this.selectedIndex].text;
        clearSelection();
        colorearDiasBloqueados();
    });

    tipoSelect.addEventListener('change', function() { updateSelectedColor(); });
    updateSelectedColor();

    const form = document.querySelector('.special-form');
    form.addEventListener('submit', function(e) {
        const count = parseInt(selectedCount.textContent || '0', 10);
        if (count === 0) {
            e.preventDefault();
            Swal.fire({ icon: 'info', title: 'Faltan fechas', text: 'Debes seleccionar al menos un día en el calendario antes de guardar.' });
            return false;
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/dias_especiales/index.blade.php ENDPATH**/ ?>