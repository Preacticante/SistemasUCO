

<?php $__env->startSection('title', 'Días Especiales'); ?>
<?php $__env->startSection('header', 'Días Especiales'); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-container">
    <div class="dashboard-header">
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
        <div class="alert alert-danger">
            <i class="fa-solid fa-triangle-exclamation"></i> Por favor revisa los errores del formulario.
            <ul style="margin-top: 5px; margin-left: 20px;">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

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
                    <label for="empleados">Seleccionar trabajadores</label>
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
                        
                        <form action="<?php echo e(route('dias-especiales.destroy', $dia->id)); ?>" method="POST" class="form-eliminar" style="display:inline;">
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

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.css">

<style>
    /* Alertas de cabecera de Laravel */
    .alert {
        padding: 16px;
        margin-bottom: 20px;
        border-radius: 16px;
        font-weight: 600;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 10px;
        animation: fadeIn 0.3s ease-in-out;
    }
    .alert-success {
        background-color: #ecfdf5;
        border: 1px solid #10b981;
        color: #065f46;
    }
    .alert-danger {
        background-color: #fef2f2;
        border: 1px solid #ef4444;
        color: #991b1b;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-5px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Estilos estéticos personalizados para SweetAlert2 basados en tu paleta de colores */
    .swal2-popup {
        border-radius: 24px !important;
        padding: 2rem !important;
        font-family: inherit !important;
    }
    .swal2-title {
        color: #0f172a !important;
        font-weight: 700 !important;
        font-size: 1.6rem !important;
    }
    .swal2-html-container {
        color: #475569 !important;
        font-size: 1rem !important;
    }
    .swal2-confirm {
        border-radius: 9999px !important;
        padding: 12px 30px !important;
        font-weight: 700 !important;
        background-color: #ef4444 !important; /* Rojo idéntico a image_9098e7.png */
    }
    .swal2-cancel {
        border-radius: 9999px !important;
        padding: 12px 30px !important;
        font-weight: 700 !important;
        background-color: #3b82f6 !important; /* Azul idéntico a image_9098e7.png */
    }

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
    .special-form { display: grid; gap: 16px; }
    .form-group { display: flex; flex-direction: column; gap: 10px; }
    .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .special-form label { color: #334155; font-weight: 700; font-size: 0.95rem; }
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
    .table-uco th, .table-uco td { padding: 14px 16px; border-bottom: 1px solid #e2e8f0; }
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

    #calendar-inline { border-radius: 24px; overflow: hidden; border: 1px solid #e2e8f0; }
    .calendar-controls { margin-bottom: 16px; color: #475569; font-size: 0.95rem; }
    
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
    .flatpickr-day.today { color: #124416; font-weight: 700; }
    .flatpickr-day.has-special { opacity: 1 !important; color: inherit !important; }

    @media (max-width: 1080px) {
        .special-days-form-grid { grid-template-columns: 1fr; }
    }
    /* Clases prioritarias para forzar el pintado sólido de los días bloqueados/históricos */
.flatpickr-day.bloqueado-descanso, 
.flatpickr-day.bloqueado-descanso:hover,
.flatpickr-day.bloqueado-descanso.flatpickr-disabled {
    background: #124416 !important;
    color: #ffffff !important;
    border-color: #124416 !important;
    opacity: 1 !important;
    cursor: not-allowed;
}

.flatpickr-day.bloqueado-festivo, 
.flatpickr-day.bloqueado-festivo:hover,
.flatpickr-day.bloqueado-festivo.flatpickr-disabled {
    background: #AA7F31 !important;
    color: #ffffff !important;
    border-color: #AA7F31 !important;
    opacity: 1 !important;
    cursor: not-allowed;
}

.flatpickr-day.bloqueado-institucional, 
.flatpickr-day.bloqueado-institucional:hover,
.flatpickr-day.bloqueado-institucional.flatpickr-disabled {
    background: #340C51 !important;
    color: #ffffff !important;
    border-color: #340C51 !important;
    opacity: 1 !important;
    cursor: not-allowed;
}
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

    // Paleta de colores exacta del sistema
    const colors = { descanso: '#124416', festivo: '#AA7F31', institucional: '#340C51' };
    const textColors = { descanso: '#ffffff', festivo: '#ffffff', institucional: '#ffffff' };

    function updateSelectedColor() {
        const type = tipoSelect.value || 'descanso';
        document.documentElement.style.setProperty('--selected-day-bg', colors[type]);
        document.documentElement.style.setProperty('--selected-day-color', textColors[type]);
        
        if (fp) {
            if (type === 'descanso') { 
                fp.set('disable', disabledForDescanso); 
            } else { 
                fp.set('disable', []); 
            }
            // Ejecutamos nuestra función de repintado manual justo después de redibujar Flatpickr
            fp.redraw();
            colorearDiasBloqueados();
        }
    }

    // NUEVA FUNCIÓN: Fuerza el pintado de las clases recorriendo los elementos del DOM actuales del calendario
    function colorearDiasBloqueados() {
        if (!fp) return;
        
        // Buscamos todas las celdas de días visibles en el Flatpickr actual
        const dayElements = fp.calendarContainer.querySelectorAll('.flatpickr-day');
        
        dayElements.forEach(dayElement => {
            const ds = dayElement.dateObj ? fp.formatDate(dayElement.dateObj, 'Y-m-d') : null;
            
            if (ds && specialDateMap[ds]) {
                const tipoDiaHistorial = specialDateMap[ds].tipo;
                
                // Limpiamos residuos de selección nativa para evitar que el azul/gris de Flatpickr tape nuestro color
                dayElement.classList.remove('selected', 'startRange', 'endRange');
                
                // Forzamos la clase correspondiente
                if (tipoDiaHistorial === 'descanso') {
                    dayElement.classList.add('bloqueado-descanso');
                } else if (tipoDiaHistorial === 'festivo') {
                    dayElement.classList.add('bloqueado-festivo');
                } else if (tipoDiaHistorial === 'institucional') {
                    dayElement.classList.add('bloqueado-institucional');
                }
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
    // Clonamos y forzamos el inicio del día local
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
    // Si la cadena contiene una 'T' u horas de la API, nos quedamos solo con la parte 'AAAA-MM-DD'
    const cleanStr = String(dateStr).split('T')[0];
    const parts = cleanStr.split('-').map(p => parseInt(p, 10));
    
    if (parts.length === 3) {
        // El mes en JS va de 0 a 11, por eso restamos 1
        return new Date(parts[0], parts[1] - 1, parts[2]);
    }
    return new Date(cleanStr); // Fallback seguro
}

    function formatYMD(date) {
        if (!date) return '';
        const y = date.getFullYear();
        const m = String(date.getMonth() + 1).padStart(2, '0');
        const d = String(date.getDate()).padStart(2, '0');
        return `${y}-${m}-${d}`;
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

    let fp;

    function createSpecialDayCalendar() {
        fp = flatpickr('#calendar-inline', {
            inline: true,
            mode: 'multiple',
            locale: 'es',
            dateFormat: 'Y-m-d',
            defaultDate: inputHiddenDates.value ? inputHiddenDates.value.split(',') : [],
            onDayCreate: function(dObj, dStr, dayElement) {
                // Ejecutamos el mapeo inicial al crear los nodos
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
                        Swal.fire({ icon: 'warning', title: 'Fecha bloqueada', text: 'Esa fecha está marcada como festivo o vacaciones institucionales y no puede ser seleccionada como descanso.' });
                        return;
                    }

                    if (mode === 'semana') {
                        const [start, end] = getWeekRange(dayElement.dateObj);
                        const dates = getDatesBetween(start, end).map(d => fp.formatDate(d, 'Y-m-d'));
                        if (type === 'descanso' && dates.some(d => disabledForDescansoSet.has(d))) {
                            Swal.fire({ icon: 'error', title: 'Conflicto de fechas', text: 'La semana contiene días festivos o institucionales, no se puede seleccionar.' });
                            return;
                        }
                        selectRange(start, end);
                    } else if (mode === 'mes') {
                        const [start, end] = getMonthRange(dayElement.dateObj);
                        const dates = getDatesBetween(start, end).map(d => fp.formatDate(d, 'Y-m-d'));
                        if (type === 'descanso' && dates.some(d => disabledForDescansoSet.has(d))) {
                            Swal.fire({ icon: 'error', title: 'Conflicto de fechas', text: 'El mes contiene días festivos o institucionales, no se puede seleccionar.' });
                            return;
                        }
                        selectRange(start, end);
                    }
                });
            },
            // NUEVO: Cada que el usuario cambie de mes o de año, volvemos a aplicar las clases a los nuevos elementos renderizados
            onMonthChange: function() {
                setTimeout(colorearDiasBloqueados, 10);
            },
            onYearChange: function() {
                setTimeout(colorearDiasBloqueados, 10);
            },
            onChange: function(selectedDates) {
                const mode = selectionMode.value;
                if (mode === 'varios' || mode === 'personalizado') { 
                    updateFields(selectedDates); 
                }
                if (selectedDates.length === 0) { 
                    updateFields([]); 
                }
                fp.redraw();
                colorearDiasBloqueados(); // Asegurar colores tras los cambios
            }
        });

        // Forzar pintado inmediatamente después de que se cree el objeto flatpickr por primera vez
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
            
            // CORRECCIÓN: Si el evento tiene fecha de fin y no es el mismo día de inicio,
            // le restamos 1 día para corregir el desfase del estándar de calendarios.
            if (ev.end && ev.start !== ev.end) {
                end.setDate(end.getDate() - 1);
            }
            
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

    tipoSelect.addEventListener('change', function() { 
        updateSelectedColor(); 
    });
    
    updateSelectedColor();

    // ==========================================
    // VALIDACIONES DEL FORMULARIO DE GUARDADO
    // ==========================================
    const form = document.querySelector('.special-form');
    const empleadosSelect = document.getElementById('empleados');

    form.addEventListener('submit', function(e) {
        const tipoDia = tipoSelect.value;
        const mode = selectionMode.value;
        const count = parseInt(selectedCount.textContent || '0', 10);

        if (count === 0) {
            e.preventDefault();
            Swal.fire({ icon: 'info', title: 'Faltan fechas', text: 'Debes seleccionar al menos un día en el calendario antes de guardar.' });
            return false;
        }

        if (mode === 'semana' && count !== 7) {
            e.preventDefault();
            Swal.fire({ icon: 'warning', title: 'Selección incompleta', text: 'Selección semanal: debes elegir exactamente 7 días.' });
            return false;
        }

        if (mode === 'mes') {
            const start = inputFechaInicio.value ? parseYMDToLocal(inputFechaInicio.value) : null;
            if (!start) {
                e.preventDefault();
                Swal.fire({ icon: 'warning', title: 'Selección incompleta', text: 'Selección por mes: selecciona una fecha dentro del mes deseado.' });
                return false;
            }
            const lastDay = new Date(start.getFullYear(), start.getMonth() + 1, 0).getDate();
            if (count !== lastDay) {
                e.preventDefault();
                Swal.fire({ icon: 'warning', title: 'Días faltantes', text: 'Selección por mes: debes seleccionar todos los días del mes (' + lastDay + ').' });
                return false;
            }
        }

        if (tipoDia === 'descanso') {
            const anySelected = Array.from(empleadosSelect.options).some(o => o.selected);
            if (!anySelected) {
                e.preventDefault();
                Swal.fire({ icon: 'info', title: 'Asignación requerida', text: 'Para el tipo "Descanso de trabajador" es obligatorio seleccionar al menos un trabajador.' });
                return false;
            }
        }
    });

    // ==========================================
    // ALERTA ESTÉTICA DE ELIMINACIÓN
    // ==========================================
    document.querySelectorAll('.form-eliminar').forEach(formEliminar => {
        formEliminar.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Esta acción eliminará el registro del día especial de forma permanente.',
                icon: 'warning',
                iconColor: '#f8bb86',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true   
            }).then((result) => {
                if (result.isConfirmed) {
                    formEliminar.submit(); 
                }
            });
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/dias_especiales/index.blade.php ENDPATH**/ ?>