<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vacaciones | <?php echo e($empleado->nombre); ?> <?php echo e($empleado->apellido_paterno); ?> <?php echo e($empleado->apellido_materno); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body>
    <main class="page-shell">

        <?php
            $path = public_path('img/logo_uco.png');
            $base64 = '';
            if (file_exists($path)) {
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
        ?>

        <?php if($base64): ?>
            <div class="logo-outer-container">
                <img src="<?php echo e($base64); ?>" alt="Logo UCO" class="logo-outside">
            </div>
        <?php endif; ?>
        
        <section class="topbar">
            <div class="topbar-left">
                <div class="topbar-title">
                    <span class="page-label">Vacaciones</span>
                    <h1><?php echo e($empleado->nombre); ?> <?php echo e($empleado->apellido_paterno); ?> <?php echo e($empleado->apellido_materno); ?></h1>
                </div>
                <p>Administra registros y consulta el consumo de vacaciones de manera clara.</p>
            </div>

            <a href="#" onclick="history.back(); return false;" class="button-link"> 
                <i class="fa-solid fa-arrow-left button-icon-svg"></i>
                Volver
            </a>
        </section>

        <?php if($errors->any()): ?>
            <div class="status error" style="display: none;">
                <ul id="laravel-errors-list">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <section class="summary-grid">
            <article class="card">
                <div class="card-header-title">
                    <i class="fa-solid fa-user-gear"></i> Información del empleado
                </div>
                <div class="meta-row">
                    <span class="meta-pill">Puesto: <strong><?php echo e($puestoNombre); ?></strong></span>
                    <span class="meta-pill">Ingreso: <strong><?php echo e(\Carbon\Carbon::parse($empleado->fecha_ingreso)->format('d/m/Y')); ?></strong></span>
                    <span class="meta-pill">Antigüedad: <strong><?php echo e($antiguedadAnios); ?> años</strong></span>
                    <span class="meta-pill status-derecho">Derecho anual: <strong><?php echo e($diasDerecho); ?> días</strong></span>
                </div>
            </article>

            <article class="card">
                <div class="card-header-title">
                    <i class="fa-solid fa-chart-pie"></i> Estado actual
                </div>
                <div class="meta-row">
                    <span class="meta-pill status-tomados">Tomados: <strong><?php echo e($diasTomados); ?></strong></span>
                    <span class="meta-pill status-restantes">Restantes: <strong><?php echo e($diasRestantes); ?></strong></span>
                    <span class="meta-pill">Año: <strong><?php echo e($anioActual); ?></strong></span>
                </div>
            </article>
        </section>

        <section class="card-columns">
            <article class="card">
                <h2><i class="fa-solid fa-calendar-plus" style="color: #124416;"></i> Registrar vacaciones</h2>
                <form id="form-vacaciones" action="<?php echo e(route('empleados.vacaciones.guardar', $empleado->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    
                    <input type="hidden" id="multiple_dates" name="multiple_dates" value="<?php echo e(old('multiple_dates')); ?>">
                    <input type="hidden" id="fecha_inicio" name="fecha_inicio" value="<?php echo e(old('fecha_inicio')); ?>">
                    <input type="hidden" id="fecha_fin" name="fecha_fin" value="<?php echo e(old('fecha_fin')); ?>">

                    <div class="form-group">
                        <label for="calendar-inline">Selecciona los días en el calendario</label>
                        <div id="calendar-inline"></div>
                    </div>

                    <div class="form-group">
                        <label for="dias_solicitados">Días seleccionados a descontar</label>
                        <input type="number" id="dias_solicitados" name="dias_solicitados" value="<?php echo e(old('dias_solicitados', 0)); ?>" min="0" step="1" readonly required />
                        <div class="form-instruction" style="margin-top: 5px;">
                            * El sistema calcula los días contando de manera exacta cada fecha marcada en color morado.
                        </div>
                    </div>
                    <div class="form-group" style="margin-top: 1.5rem;">
                        <label for="observaciones">Observaciones / Motivo</label>
                        <textarea id="observaciones" name="observaciones" rows="3" placeholder="Escribe aquí algún comentario u observación sobre este período (opcional)..." class="form-textarea"><?php echo e(old('observaciones')); ?></textarea>
                    </div>

                    <div class="meta-row" style="margin-bottom: 1.5rem;">
                        <span class="meta-pill">Restantes estimados: <strong id="preview-restantes" style="color: #124416;"><?php echo e(max(0, $diasRestantes)); ?></strong></span>
                    </div>

                    <button type="submit" class="button-primary">
                        <i class="fa-solid fa-floppy-disk"></i> Guardar registro
                    </button>
                </form>
            </article>

            <article class="card">
                <h2><i class="fa-solid fa-calendar-days" style="color: #124416;"></i> Consumo mensual</h2>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Mes</th>
                                <th style="text-align: center;">Días tomados</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $meses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $numero => $nombre): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="td-mes"><?php echo e($nombre); ?></td>
                                    <td style="text-align: center;"><strong class="dias-count-table"><?php echo e($registroPorMes[$numero]); ?></strong></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <div style="margin-top: 1.5rem; display: flex; justify-content: flex-end; gap: 8px; flex-wrap: wrap;">
                    <a href="<?php echo e(route('empleados.vacaciones.pdf', $empleado->id)); ?>" target="_blank" rel="noopener noreferrer" class="button-secondary">
                        <i class="fa-solid fa-file-pdf"></i> Reporte PDF
                    </a>
                    <a href="<?php echo e(route('empleados.vacaciones.historial.pdf', $empleado->id)); ?>" target="_blank" rel="noopener noreferrer" class="button-secondary">
                        <i class="fa-solid fa-clock-rotate-left"></i> Historial
                    </a>
                </div>
            </article>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const diasRestantes = <?php echo e($diasRestantes); ?>;
    const inputHiddenDates = document.getElementById('multiple_dates');
    const inputFechaInicio = document.getElementById('fecha_inicio');
    const inputFechaFin = document.getElementById('fecha_fin');
    const inputDias = document.getElementById('dias_solicitados');
    const previewRestantes = document.getElementById('preview-restantes');
    const formVacaciones = document.getElementById('form-vacaciones');

    // Mapeos para procesar los eventos de la API externa
    const specialDateMap = {}; 
    const disabledForDescanso = [];

    // --- MANEJO DE ALERTAS DE SESIÓN (Laravel -> SweetAlert2) ---
    document.addEventListener("DOMContentLoaded", () => {
        <?php if(session('success')): ?>
            Swal.fire({
                icon: 'success',
                title: '¡Operación Exitosa!',
                text: "<?php echo e(session('success')); ?>",
                confirmButtonColor: '#124416'
            });
        <?php endif; ?>

        const errorsList = document.getElementById('laravel-errors-list');
        if (errorsList) {
            let errorText = '';
            errorsList.querySelectorAll('li').forEach(li => {
                errorText += li.textContent + '\n';
            });
            Swal.fire({
                icon: 'error',
                title: 'No se pudo guardar',
                text: errorText,
                confirmButtonColor: '#340C51'
            });
        }
    });

    // --- FUNCIONES AUXILIARES ---
    function formatISO(d) {
        const y = d.getFullYear();
        const m = String(d.getMonth() + 1).padStart(2, '0');
        const day = String(d.getDate()).padStart(2, '0');
        return `${y}-${m}-${day}`;
    }

    function getDatesBetween(start, end) {
        const dates = [];
        const cur = new Date(start);
        while (cur <= end) {
            dates.push(new Date(cur));
            cur.setDate(cur.getDate() + 1);
        }
        return dates;
    }

    function updateSelectedDayColors(instance) {
        if (!instance || !instance.calendarContainer) return;

        const dayElements = instance.calendarContainer.querySelectorAll('.flatpickr-day');
        dayElements.forEach(dayElement => {
            const ds = dayElement.dateObj ? formatISO(dayElement.dateObj) : null;
            const special = ds ? specialDateMap[ds] : null;

            dayElement.classList.remove('bloqueado-descanso', 'bloqueado-festivo', 'bloqueado-institucional');
            dayElement.style.removeProperty('--day-color');

            if (special) {
                dayElement.style.setProperty('--day-color', special.color);
                if (special.tipo === 'descanso') {
                    dayElement.classList.add('bloqueado-descanso');
                } else if (special.tipo === 'festivo') {
                    dayElement.classList.add('bloqueado-festivo');
                } else if (special.tipo === 'institucional') {
                    dayElement.classList.add('bloqueado-institucional');
                }
            } else if (dayElement.classList.contains('selected') || dayElement.classList.contains('startRange') || dayElement.classList.contains('endRange')) {
                const defaultColor = getComputedStyle(document.documentElement).getPropertyValue('--morado-uco').trim() || '#340C51';
                dayElement.style.setProperty('--day-color', defaultColor);
            }
        });
    }

    // --- CARGAR EVENTOS DESDE LA API ---
    fetch('/api/eventos-vacaciones')
    .then(r => r.json())
    .then(events => {
        events.forEach(ev => {
             const tipo = ev.extendedProps ? ev.extendedProps.tipo : null;
            
            // SOLUCIÓN DEFINITIVA: Extraer Año, Mes y Día manualmente para evitar conversiones UTC
            const parseLocal = (dateString) => {
                if (!dateString) return null;
                const justDate = dateString.split('T')[0].split(' ')[0]; 
                const partes = justDate.split('-'); 
                return new Date(partes[0], partes[1] - 1, partes[2]);
            };

            const start = ev.start ? parseLocal(ev.start) : null;
            let end = ev.end ? parseLocal(ev.end) : start;
            
            if (!start) return;

            if (ev.end && ev.start !== ev.end) {
                end.setDate(end.getDate() - 1);
            }

            const dates = getDatesBetween(start, end).map(d => formatISO(d));
            
            dates.forEach(d => {
                let color = ev.backgroundColor || ev.color;
                if (!color && tipo === 'vacaciones') {
                    color = '#340C51'; 
                }

                specialDateMap[d] = { tipo: tipo, color: color || '#ccc' };

                if (tipo === 'festivo' || tipo === 'institucional') {
                    if (!disabledForDescanso.includes(d)) {
                        disabledForDescanso.push(d);
                    }
                }
            });
        });

        initFlatpickr();
    })
    .catch((err) => {
        console.error("Error al cargar eventos:", err);
        initFlatpickr();
    });

    // --- INICIALIZAR CALENDARIO ---
    function initFlatpickr() {
        flatpickr("#calendar-inline", {
            inline: true,
            mode: "multiple",
            locale: "es",
            dateFormat: "Y-m-d",
            conjunction: ",",
            defaultDate: inputHiddenDates.value ? inputHiddenDates.value.split(",") : [],
            disable: disabledForDescanso, 
            onDayCreate: function(dObj, dStr, dayElement) {
                const ds = dayElement.dateObj ? formatISO(dayElement.dateObj) : null;
                if (ds && specialDateMap[ds]) {
                    const special = specialDateMap[ds];
                    dayElement.classList.add('has-special');
                    dayElement.style.setProperty('--day-color', special.color);
                }
            },
            onReady: function(selectedDates, dateStr, instance) {
                updateSelectedDayColors(instance);
            },
            onMonthChange: function(selectedDates, dateStr, instance) {
                setTimeout(() => updateSelectedDayColors(instance), 10);
            },
            onYearChange: function(selectedDates, dateStr, instance) {
                setTimeout(() => updateSelectedDayColors(instance), 10);
            },
            onChange: function(selectedDates, dateStr, instance) {
                const sortedDates = selectedDates.slice().sort((a, b) => a - b);
                const dateStrings = sortedDates.map(date => instance.formatDate(date, "Y-m-d"));

                inputHiddenDates.value = dateStrings.join(",");
                inputDias.value = dateStrings.length;
                inputFechaInicio.value = dateStrings.length ? dateStrings[0] : '';
                inputFechaFin.value = dateStrings.length ? dateStrings[dateStrings.length - 1] : '';
                
                // Actualización del remanente estimado en tiempo real
                const calculoRestantes = diasRestantes - dateStrings.length;
                previewRestantes.textContent = calculoRestantes;

                if (calculoRestantes < 0) {
                    previewRestantes.style.color = "#b91c1c";
                } else {
                    previewRestantes.style.color = "#124416";
                }

                updateSelectedDayColors(instance);
            }
        });
    }

    // --- VALIDACIONES AL ENVIAR FORMULARIO ---
    formVacaciones.addEventListener('submit', function(e) {
        const seleccionados = parseInt(inputDias.value) || 0;

        if (seleccionados === 0) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Calendario Vacío',
                text: 'Por favor, selecciona al menos un día en el calendario antes de guardar el registro.',
                confirmButtonColor: '#AA7F31'
            });
            return false;
        }

        if (seleccionados > diasRestantes) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Días Insuficientes',
                text: `Estás intentando solicitar ${seleccionados} días, pero el empleado sólo dispone de ${diasRestantes} días restantes.`,
                confirmButtonColor: '#340C51'
            });
            return false;
        }
    });
</script>
</body>
</html>

<style>
    :root {
        --verde-uco: #124416;
        --dorado-uco: #AA7F31;
        --morado-uco: #340C51;
        --bg-body: #f1f5f9;
        --text-main: #1e293b;
        --text-muted: #64748b;
    }

    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        min-height: 100vh;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        background: radial-gradient(circle at top left, rgba(18, 68, 22, 0.05), transparent 35%),
                    radial-gradient(circle at bottom right, rgba(170, 127, 49, 0.05), transparent 30%),
                    linear-gradient(180deg, #f1f5f9 0%, #edf2f7 100%);
        color: var(--text-main);
    }

    .page-shell {
        width: min(1240px, calc(100% - 2rem));
        margin: 0 auto 2rem;
        padding: 1.5rem 0;
    }

    .logo-outer-container {
        width: 100%;
        display: flex;
        justify-content: flex-start;
        padding-left: 0.5rem;
        margin-bottom: 1rem;
    }

    .logo-outside {
        height: 160px;
        width: auto;
        object-fit: contain;
    }

    .topbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 2rem;
        padding: 2rem 2.5rem;
        border-radius: 20px;
        background: var(--verde-uco);
        box-shadow: 0 10px 25px rgba(18, 68, 22, 0.15);
        border-bottom: 5px solid var(--dorado-uco);
    }

    .topbar-left {
        display: flex;
        flex-direction: column;
    }

    .topbar-title {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 1.2rem;
    }

    .page-label {
        display: inline-flex;
        padding: 0.4rem 1rem;
        border-radius: 999px;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        font-size: 0.75rem;
        font-weight: 700;
        color: white;
        background: var(--morado-uco); 
    }

    .topbar h1 {
        margin: 0;
        font-size: 1.8rem;
        color: #ffffff;
        letter-spacing: -0.01em;
        font-weight: 700;
    }

    .topbar p {
        margin: 0.6rem 0 0;
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.98rem;
    }

    .button-link {
        border: none;
        border-radius: 20px;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        font-weight: 600;
        padding: 0.7rem 1.4rem;
        color: var(--verde-uco);
        background: #ffffff;
        box-shadow: 0 4px 10px rgba(0,0,0,0.06);
        font-size: 0.9rem;
    }

    .button-link:hover {
        transform: translateY(-2px);
        background: var(--dorado-uco);
        color: #ffffff;
        box-shadow: 0 6px 15px rgba(170, 127, 49, 0.3);
    }

    .button-icon-svg {
        margin-right: 8px;
        font-size: 0.95rem;
    }

    .button-primary {
        width: 100%;
        padding: 0.9rem;
        background: var(--verde-uco);
        color: #ffffff;
        font-size: 1rem;
        font-weight: 600;
        border: none;
        border-radius: 12px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        box-shadow: 0 4px 12px rgba(18, 68, 22, 0.2);
        transition: all 0.3s ease;
    }

    .button-primary:hover {
        transform: translateY(-2px);
        background-color: #0e3511;
        box-shadow: 0 6px 18px rgba(18, 68, 22, 0.35);
    }

    .button-secondary {
        background: #ffffff;
        color: var(--dorado-uco);
        border: 2px solid var(--dorado-uco);
        padding: 0.6rem 1.4rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .button-secondary:hover {
        background: var(--dorado-uco);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(170, 127, 49, 0.2);
    }

    .summary-grid {
        display: grid;
        gap: 1.5rem;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        margin-bottom: 2rem;
    }

    .card {
        background: #ffffff;
        border-radius: 20px;
        padding: 1.8rem;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.03);
        border: 1px solid rgba(0, 0, 0, 0.04);
        border-bottom: 4px solid var(--dorado-uco);
    }

    .card-header-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--verde-uco);
        margin-bottom: 1.2rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .card h2 {
        margin: 0 0 1.2rem 0;
        color: var(--verde-uco);
        font-size: 1.2rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .card-columns {
        display: grid;
        gap: 1.5rem;
        grid-template-columns: 1.2fr 1fr;
    }

    .meta-row {
        display: flex;
        flex-wrap: wrap;
        gap: 0.8rem;
    }

    .meta-pill {
        padding: 0.6rem 1.1rem;
        border-radius: 12px;
        background: #f8fafc;
        color: #475569;
        font-size: 0.9rem;
        border: 1px solid #e2e8f0;
    }
    
    .meta-pill strong {
        color: var(--text-main);
        display: inline;
    }

    .status-derecho {
        background: rgba(18, 68, 22, 0.05);
        border-color: rgba(18, 68, 22, 0.15);
    }
    .status-derecho strong { color: var(--verde-uco); }

    .status-tomados {
        background: rgba(185, 28, 28, 0.05);
        border-color: rgba(185, 28, 28, 0.15);
    }
    .status-tomados strong { color: #b91c1c; }

    .status-restantes {
        background: rgba(18, 68, 22, 0.08);
        border-color: rgba(18, 68, 22, 0.2);
    }
    .status-restantes strong { color: #124416; font-size: 1rem; }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-instruction {
        margin-bottom: 0.8rem; 
        color: var(--text-muted); 
        font-size: 0.9rem;
        font-style: italic;
    }

    label {
        display: block;
        margin-bottom: 0.5rem;
        color: #334155;
        font-size: 0.9rem;
        font-weight: 600;
    }

    input[type="number"] {
        width: 100%;
        padding: 0.8rem 1rem;
        border-radius: 12px;
        border: 1px solid #cbd5e1;
        background: #e2e8f0; 
        color: #1e293b;
        font-size: 0.95rem;
        font-family: inherit;
        outline: none;
    }

    .flatpickr-calendar {
        width: 100% !important;
        max-width: 350px;
        margin: 0 auto;
        box-shadow: none !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 16px !important;
        padding: 8px;
    }

    /* 1. Días especiales/eventos (Inactivos o de fondo previo) */
    .flatpickr-day.has-special {
        background-color: var(--day-color) !important;
        background-image: linear-gradient(rgba(255,255,255,0.85), rgba(255,255,255,0.85)) !important;
        border-bottom: 3px solid var(--day-color) !important;
        color: var(--text-main) !important;
        opacity: 1 !important;
    }

    .flatpickr-day.has-special:hover {
        background-image: linear-gradient(rgba(255,255,255,0.7), rgba(255,255,255,0.7)) !important;
    }

    .flatpickr-day.bloqueado-descanso,
    .flatpickr-day.bloqueado-descanso:hover,
    .flatpickr-day.bloqueado-descanso.flatpickr-disabled {
        background: #124416 !important;
        color: #ffffff !important;
        border-color: #124416 !important;
        opacity: 1 !important;
    }

    .flatpickr-day.bloqueado-festivo,
    .flatpickr-day.bloqueado-festivo:hover,
    .flatpickr-day.bloqueado-festivo.flatpickr-disabled {
        background: #AA7F31 !important;
        color: #ffffff !important;
        border-color: #AA7F31 !important;
        opacity: 1 !important;
    }

    .flatpickr-day.bloqueado-institucional,
    .flatpickr-day.bloqueado-institucional:hover,
    .flatpickr-day.bloqueado-institucional.flatpickr-disabled {
        background: #340C51 !important;
        color: #ffffff !important;
        border-color: #340C51 !important;
        opacity: 1 !important;
    }

    .flatpickr-day.selected:not(.has-special),
    .flatpickr-day.selected:not(.has-special):hover,
    .flatpickr-day.selected:not(.has-special):focus,
    .flatpickr-day.startRange:not(.has-special),
    .flatpickr-day.endRange:not(.has-special) {
        background: var(--morado-uco) !important;
        border: 1px solid var(--morado-uco) !important;
        border-bottom: 1px solid var(--morado-uco) !important;
        color: #ffffff !important;
        font-weight: 600 !important;
        border-radius: 8px !important;
        opacity: 1 !important;
    }

    /* 2. DÍAS SELECCIONADOS POR EL USUARIO */
    .flatpickr-day.selected.has-special,
    .flatpickr-day.selected.has-special:hover,
    .flatpickr-day.selected.has-special:focus,
    .flatpickr-day.startRange.has-special,
    .flatpickr-day.endRange.has-special {
        background: var(--day-color) !important;
        background-image: linear-gradient(rgba(255,255,255,0.85), rgba(255,255,255,0.85)) !important;
        border: 1px solid var(--day-color) !important;
        border-bottom: 3px solid var(--day-color) !important;
        color: var(--text-main) !important;
        opacity: 1 !important;
    }

    .flatpickr-day:hover {
        background: rgba(52, 12, 81, 0.1) !important;
    }

    .flatpickr-months .flatpickr-month {
        color: var(--verde-uco) !important;
        fill: var(--verde-uco) !important;
    }

    .flatpickr-current-month .flatpickr-monthDropdown-months {
        font-weight: 700 !important;
    }

    .flatpickr-weekdays {
        background: transparent !important;
    }

    span.flatpickr-weekday {
        color: var(--text-muted) !important;
        font-weight: 600 !important;
    }

    /* --- TABLA --- */
    .table-wrapper {
        overflow-x: auto;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 0.9rem 1.2rem;
        text-align: left;
        font-size: 0.92rem;
    }

    th {
        background: var(--verde-uco);
        color: #ffffff;
        font-weight: 600;
        letter-spacing: 0.02em;
    }

    td {
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
    }

    tr:last-child td { border-bottom: none; }
    tr:hover td { background-color: #f8fafc; }
    .td-mes { font-weight: 500; }
    .dias-count-table { color: var(--morado-uco); font-size: 0.95rem; }

    .status {
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        font-size: 0.95rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    textarea.form-textarea {
        width: 100%;
        padding: 0.8rem 1rem;
        border-radius: 12px;
        border: 1px solid #cbd5e1;
        background: #ffffff;
        color: #1e293b;
        font-size: 0.95rem;
        font-family: inherit;
        outline: none;
        resize: vertical;
        transition: border-color 0.3s ease;
    }

    textarea.form-textarea:focus {
        border-color: var(--verde-uco);
        box-shadow: 0 0 0 3px rgba(18, 68, 22, 0.1);
    }

    .status.error { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

    /* --- MEDIA QUERIES --- */
    @media (max-width: 1024px) {
        .page-shell { width: 100%; padding: 1rem; }
        .summary-grid, .card-columns { grid-template-columns: 1fr; }
        .card { padding: 1.5rem; }
    }

    @media (max-width: 768px) {
        .topbar { flex-direction: column; align-items: flex-start; padding: 1.2rem; gap: 1rem; }
        .topbar-title { flex-direction: column; gap: 0.5rem; }
        .topbar h1 { font-size: 1.4rem; }
        .button-link { width: 100%; }
        .logo-outer-container { justify-content: center; padding-left: 0; }
        .logo-outside { height: 80px; }
        .meta-row { flex-direction: column; gap: 0.5rem; }
        .meta-pill { width: 100%; }
    }
</style><?php /**PATH /var/www/html/resources/views/empleados/vacaciones.blade.php ENDPATH**/ ?>