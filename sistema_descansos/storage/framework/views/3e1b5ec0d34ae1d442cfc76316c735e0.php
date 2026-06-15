

<?php $__env->startSection('title', 'Días Especiales'); ?>
<?php $__env->startSection('header', 'Días Especiales'); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-container">
    <div class="dashboard-header">
        <h1>Gestión de días especiales</h1>
        <p>Agrega y administra descansos semanales, días festivos y vacaciones institucionales con selección personalizada.</p>
    </div>

    <div class="special-days-hero">
        <div class="special-day-card special-day-descanso">
            <strong>Descanso de trabajador </strong>
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
            <h2><i class="fa-solid fa-calendar-plus" style="color: #124416;"></i> Registrar día especial</h2>
            <form action="<?php echo e(route('dias-especiales.store')); ?>" method="POST" class="special-form">
                <?php echo csrf_field(); ?>
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
                    <label for="titulo">Título</label>
                    <input id="titulo" type="text" name="titulo" placeholder="Ej: Descanso semanal" value="<?php echo e(old('titulo')); ?>" required>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label>Inicio</label>
                        <input id="fecha_inicio_display" type="text" readonly placeholder="dd/mm/yyyy">
                    </div>
                    <div class="form-group">
                        <label>Fin</label>
                        <input id="fecha_fin_display" type="text" readonly placeholder="dd/mm/yyyy">
                    </div>
                </div>

                <div class="form-group">
                    <label for="selection_mode">Personalizado</label>
                    <select id="selection_mode" name="selection_mode">
                        <option value="personalizado">Personalizado</option>
                        <option value="semana">Por semana</option>
                        <option value="mes">Por mes</option>
                    
                    </select>
                </div>

                

                <div class="form-group">
                    <label for="empleados">Seleccionar trabajadores (mantén Ctrl/Cmd para multi)</label>
                    <select id="empleados" name="empleados[]" multiple size="6">
                        <?php $__currentLoopData = $empleados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($emp->id); ?>"><?php echo e($emp->nombre); ?> <?php echo e($emp->apellido_paterno); ?> <?php echo e($emp->apellido_materno); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <input type="hidden" id="multiple_dates" name="multiple_dates" value="<?php echo e(old('multiple_dates')); ?>">
                <input type="hidden" id="fecha_inicio" name="fecha_inicio" value="<?php echo e(old('fecha_inicio')); ?>">
                <input type="hidden" id="fecha_fin" name="fecha_fin" value="<?php echo e(old('fecha_fin')); ?>">

                <div class="form-group">
                    <label for="observaciones">Observaciones</label>
                    <textarea id="observaciones" name="observaciones" rows="3" placeholder="Opcional"><?php echo e(old('observaciones')); ?></textarea>
                </div>

                <div class="summary-pill">
                    <span>Seleccionados: <strong id="selected-count">0</strong></span>
                    <span>Estado: <strong id="selection-type-text">Personalizado</strong></span>
                </div>

                <button type="submit" class="btn-primary">Guardar día especial</button>
            </form>
        </article>

        <article class="special-calendar-card">
            <h2><i class="fa-solid fa-calendar-days" style="color: #124416;"></i> Selección de fechas</h2>
            <div class="calendar-controls">
                <span>Usa el calendario para marcar los días en color según el tipo seleccionado.</span>
            </div>
            <div id="calendar-inline"></div>
        </article>
    </div>

    <div class="table-wrapper">
        <h2>Lista de días especiales</h2>
        <table class="table-uco">
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Título</th>
                    <th>Inicio</th>
                    <th>Fin</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $diasEspeciales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dia): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><span class="event-pill event-<?php echo e($dia->tipo); ?>"><?php echo e(ucfirst($dia->tipo)); ?></span></td>
                    <td><?php echo e($dia->titulo); ?></td>
                    <td><?php echo e($dia->fecha_inicio->format('d/m/Y')); ?></td>
                    <td><?php echo e($dia->fecha_fin->format('d/m/Y')); ?></td>
                    <td>
                        <form action="<?php echo e(route('dias-especiales.destroy', $dia->id)); ?>" method="POST" style="display:inline;">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="5" style="text-align:center;">No hay días especiales registrados.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .special-days-hero {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 18px;
        margin-bottom: 24px;
    }
    .special-day-card {
        background: #ffffff;
        border-radius: 22px;
        padding: 22px;
        box-shadow: 0 20px 45px rgba(15, 23, 42, 0.05);
        display: flex;
        flex-direction: column;
        gap: 10px;
        min-height: 110px;
    }
    .special-day-descanso { border-left: 6px solid #124416; }
    .special-day-festivo { border-left: 6px solid #AA7F31; }
    .special-day-institucional { border-left: 6px solid #340C51; }
    .special-day-card strong { font-size: 1rem; color: #0f172a; }
    .special-day-card span { font-size: 0.95rem; color: #475569; }

    .special-days-form-grid {
        display: grid;
        grid-template-columns: 1fr 1.4fr;
        gap: 24px;
        margin-bottom: 30px;
    }
    .special-form-card,
    .special-calendar-card {
        background: #ffffff;
        border-radius: 26px;
        padding: 26px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 16px 32px rgba(15, 23, 42, 0.06);
    }
    .special-form-card h2,
    .special-calendar-card h2 {
        margin-top: 0;
        color: #124416;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .special-form {
        display: grid;
        gap: 16px;
    }
    .form-group {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .form-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }
    .special-form label {
        color: #334155;
        font-weight: 700;
        font-size: 0.95rem;
    }
    .special-form input,
    .special-form select,
    .special-form textarea {
        width: 100%;
        padding: 14px 16px;
        border: 1px solid #cbd5e1;
        border-radius: 16px;
        background: #f8fafc;
        font-size: 0.96rem;
        color: #0f172a;
    }
    .special-form textarea { min-height: 120px; resize: vertical; }
    .summary-pill {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        padding: 16px 18px;
        border-radius: 16px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #334155;
        font-weight: 700;
    }
    .btn-primary,
    .btn-danger {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: fit-content;
        border: none;
        border-radius: 9999px;
        padding: 12px 24px;
        color: white;
        font-weight: 700;
        cursor: pointer;
        transition: background-color 0.2s ease, transform 0.2s ease;
    }
    .btn-primary { background-color: #124416; }
    .btn-primary:hover { background-color: #0d3913; transform: translateY(-1px); }
    .btn-danger { background-color: #ef4444; }
    .btn-danger:hover { background-color: #dc2626; transform: translateY(-1px); }
    .table-uco { width: 100%; border-collapse: collapse; }
    .table-uco thead { background-color: #124416; color: #ffffff; }
    .table-uco th,
    .table-uco td { padding: 14px 16px; border-bottom: 1px solid #e2e8f0; }
    .table-uco tbody tr:hover { background: #f8fafc; }
    .event-pill {
        display: inline-flex;
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 0.82rem;
        font-weight: 700;
        text-transform: capitalize;
    }
    .event-descanso { background: rgba(18, 68, 22, 0.14); color: #124416; }
    .event-festivo { background: rgba(170, 127, 49, 0.16); color: #AA7F31; }
    .event-institucional { background: rgba(249, 115, 22, 0.16); color: #F97316; }

    #calendar-inline {
        border-radius: 24px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }
    .calendar-controls {
        margin-bottom: 16px;
        color: #475569;
        font-size: 0.95rem;
    }
    .flatpickr-day.selected,
    .flatpickr-day.startRange,
    .flatpickr-day.endRange {
        background: var(--selected-day-bg, #124416) !important;
        color: var(--selected-day-color, #ffffff) !important;
        border-color: var(--selected-day-bg, #124416) !important;
    }
    .flatpickr-day.selected:hover {
        background: var(--selected-day-bg, #124416) !important;
        color: var(--selected-day-color, #ffffff) !important;
    }
    .flatpickr-day.today {
        color: #124416;
        font-weight: 700;
    }

    @media (max-width: 1080px) {
        .special-days-form-grid { grid-template-columns: 1fr; }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
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

    const colors = {
        descanso: '#124416',
        festivo: '#AA7F31',
        institucional: '#340C51'
    };

    const textColors = {
        descanso: '#ffffff',
        festivo: '#000000',
        institucional: '#ffffff'
    };

    function updateSelectedColor() {
        const type = tipoSelect.value || 'descanso';
        document.documentElement.style.setProperty('--selected-day-bg', colors[type]);
        document.documentElement.style.setProperty('--selected-day-color', textColors[type]);
        // when selecting descansos, disable festivo/institucional dates
        if (fp) {
            if (type === 'descanso') {
                fp.set('disable', disabledForDescanso);
            } else {
                fp.set('disable', []);
            }
        }
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
        const current = new Date(start);
        while (current <= end) {
            dates.push(new Date(current));
            current.setDate(current.getDate() + 1);
        }
        return dates;
    }

    function selectRange(start, end) {
        const dates = getDatesBetween(start, end);
        fp.setDate(dates, true, 'Y-m-d');
        updateFields(dates);
    }

    function clearSelection() {
        fp.clear();
        updateFields([]);
    }

    const fp = flatpickr('#calendar-inline', {
        inline: true,
        mode: 'multiple',
        locale: 'es',
        dateFormat: 'Y-m-d',
        defaultDate: inputHiddenDates.value ? inputHiddenDates.value.split(',') : [],
        onDayCreate: function(dObj, dStr, dayElement) {
            // mark existing special days visually
            const ds = dayElement.dateObj ? fp.formatDate(dayElement.dateObj, 'Y-m-d') : null;
            if (ds && specialDateMap[ds]) {
                dayElement.classList.add('has-special');
                dayElement.style.borderBottom = '3px solid ' + specialDateMap[ds];
            }

            dayElement.dateObj && dayElement.addEventListener('click', function(e) {
                const mode = selectionMode.value;
                const type = tipoSelect.value;
                // prevent selecting dates that are disabled for descansos
                if (type === 'descanso' && disabledForDescansoSet.has(fp.formatDate(dayElement.dateObj, 'Y-m-d'))) {
                    e.preventDefault();
                    alert('Esa fecha está marcada como festivo o vacaciones institucionales y no puede ser seleccionada como descanso.');
                    return;
                }

                if (mode === 'semana') {
                    const [start, end] = getWeekRange(dayElement.dateObj);
                    // validate range doesn't include disabled dates
                    const dates = getDatesBetween(start, end).map(d => fp.formatDate(d, 'Y-m-d'));
                    if (type === 'descanso' && dates.some(d => disabledForDescansoSet.has(d))) {
                        alert('La semana contiene días festivos/institucionales, no se puede seleccionar.');
                        return;
                    }
                    selectRange(start, end);
                } else if (mode === 'mes') {
                    const [start, end] = getMonthRange(dayElement.dateObj);
                    const dates = getDatesBetween(start, end).map(d => fp.formatDate(d, 'Y-m-d'));
                    if (type === 'descanso' && dates.some(d => disabledForDescansoSet.has(d))) {
                        alert('El mes contiene días festivos/institucionales, no se puede seleccionar.');
                        return;
                    }
                    selectRange(start, end);
                }
            });
        },
        onChange: function(selectedDates) {
            const mode = selectionMode.value;
            if (mode === 'varios' || mode === 'personalizado') {
                updateFields(selectedDates);
            }
            if (selectedDates.length === 0) {
                updateFields([]);
            }
        }
    });

    // Load existing special events to prevent conflicts client-side
    const specialDateMap = {}; // date -> color
    const disabledForDescanso = [];
    const disabledForDescansoSet = new Set();
    fetch('/api/eventos-vacaciones').then(r => r.json()).then(events => {
        events.forEach(ev => {
            // only consider evento-especial types
            if ((ev.classNames || []).some(c => c.includes('evento-'))) {
                // mark each date in range
                const start = new Date(ev.start);
                const end = ev.end ? new Date(ev.end) : start;
                // FullCalendar end is exclusive; keep inclusive by subtracting 1 day if end provided
                if (ev.end) end.setDate(end.getDate() - 1);
                const dates = getDatesBetween(start, end).map(d => fp.formatDate(d, 'Y-m-d'));
                dates.forEach(d => {
                    specialDateMap[d] = ev.backgroundColor || ev.color || specialDateMap[d] || '#ccc';
                    // if event is festivo or institucional, mark as disabled for descanso
                    if ((ev.classNames || []).some(c => c === 'evento-festivo' || c === 'evento-institucional' || c.includes('evento-festivo') || c.includes('evento-institucional'))) {
                        if (!disabledForDescansoSet.has(d)) {
                            disabledForDescanso.push(d);
                            disabledForDescansoSet.add(d);
                        }
                    }
                });
            }
        });
        // apply disable if current type is descanso
        updateSelectedColor();
    }).catch(()=>{});

    selectionMode.addEventListener('change', function() {
        selectionText.textContent = this.options[this.selectedIndex].text;
        clearSelection();
    });

    tipoSelect.addEventListener('change', function() {
        updateSelectedColor();
    });

    updateSelectedColor();

    // Validación cliente antes de enviar
    const form = document.querySelector('.special-form');
    const aplicaTodosCheckbox = document.getElementById('aplica_todos');
    const empleadosSelect = document.getElementById('empleados');

    form.addEventListener('submit', function(e) {
        const mode = selectionMode.value;
        const count = parseInt(selectedCount.textContent || '0', 10);

        if (mode === 'semana' && count !== 7) {
            e.preventDefault();
            alert('Seleccion semanal: debes elegir exactamente 7 días.');
            return false;
        }

        if (mode === 'mes') {
            const start = inputFechaInicio.value ? new Date(inputFechaInicio.value) : null;
            if (!start) {
                e.preventDefault();
                alert('Seleccion por mes: selecciona una fecha dentro del mes deseado.');
                return false;
            }
            const lastDay = new Date(start.getFullYear(), start.getMonth() + 1, 0).getDate();
            if (count !== lastDay) {
                e.preventDefault();
                alert('Seleccion por mes: debes seleccionar todos los dias del mes (' + lastDay + ').');
                return false;
            }
        }

        // Si no aplica a todos, al menos un empleado debe estar seleccionado
        if (!aplicaTodosCheckbox.checked) {
            const anySelected = Array.from(empleadosSelect.options).some(o => o.selected);
            if (!anySelected) {
                e.preventDefault();
                alert('Selecciona al menos un trabajador o marca "Aplica a todos".');
                return false;
            }
        }
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/dias_especiales/index.blade.php ENDPATH**/ ?>