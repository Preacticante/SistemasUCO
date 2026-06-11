

<?php $__env->startSection('title', 'Historial'); ?>
<?php $__env->startSection('header', 'Directorio de vacaciones'); ?>

<?php $__env->startSection('content'); ?>
    <div class="panel-principal-header">
        <h2>Historial de Vacaciones</h2>
        <p>Resumen general del estado de vacaciones y alertas de personal activo.</p>
    </div>

    <div class="table-card-container">
        <div class="table-card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
            <span><i class="fas fa-list-ul"></i> Registro de Solicitudes</span>
            
            <form action="<?php echo e(route('historial')); ?>" method="GET" class="search-form">
                <input type="text" name="buscar" value="<?php echo e(request('buscar')); ?>" placeholder="Buscar empleado..." class="search-input">
                <button type="submit" class="btn-search" title="Buscar"><i class="fas fa-search"></i></button>
                <?php if(request('buscar')): ?>
                    <a href="<?php echo e(route('historial')); ?>" class="btn-clear-search" title="Limpiar búsqueda"><i class="fas fa-times"></i></a>
                <?php endif; ?>
            </form>
        </div>

        <div style="overflow-x: auto;">
            <table class="responsive-table-v2">
                <thead>
                    <tr>
                        <th>EMPLEADO</th>
                        <th>TIPO</th>
                        <th>FECHA INICIO</th>
                        <th>FECHA FIN</th>
                        <th>DÍAS TOTALES</th>
                        <th>ESTADO</th>
                        <th style="text-align: center;">ACCIÓN</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $periodosVacacionales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $periodo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $empleado = $periodo->empleado;
                            $fechaFin = \Carbon\Carbon::parse($periodo->fecha_fin);
                            $yaTomado = $fechaFin->isPast();
                            $estado = $yaTomado ? 'Tomado' : 'Programado';
                        ?>
                        <tr>
                            <td class="text-employee-name">
                                <?php echo e($empleado?->nombre); ?> <?php echo e($empleado?->apellido_paterno); ?> <?php echo e($empleado?->apellido_materno); ?>

                            </td>
                            <td style="color: #64748b; font-weight: 500;">
                                Vacaciones
                            </td>
                            <td style="color: #334155;">
                                <?php echo e(\Carbon\Carbon::parse($periodo->fecha_inicio)->format('d/m/Y')); ?>

                            </td>
                            <td style="color: #334155;">
                                <?php echo e($fechaFin->format('d/m/Y')); ?>

                            </td>
                            <td class="<?php echo e($yaTomado ? 'text-muted-days' : 'text-danger-bold'); ?>">
                                <?php echo e($periodo->dias); ?> día<?php echo e($periodo->dias === 1 ? '' : 's'); ?>

                            </td>
                            <td>
                                <span class="badge <?php echo e($yaTomado ? 'badge-success' : 'badge-info'); ?>">
                                    <?php echo e($estado); ?>

                                </span>
                            </td>
                            <td style="text-align: center; display: flex; gap: 8px; justify-content: center;">
                                <?php if($yaTomado): ?>
                                    <button type="button" class="btn-action-edit btn-disabled" title="No se puede editar un período ya tomado" disabled>
                                        <i class="fas fa-lock"></i> Completado
                                    </button>
                                <?php else: ?>
                                    <button type="button" class="btn-action-edit" onclick="openEditModal(<?php echo e($periodo->id); ?>, <?php echo e($empleado?->id); ?>)">
                                        <i class="fas fa-pencil"></i> 
                                    </button>
                                    <button type="button" class="btn-action-delete" onclick="deletePeriodo(<?php echo e($periodo->id); ?>)">
                                        <i class="fas fa-trash"></i> 
                                    </button>
                                    <a href="<?php echo e(route('empleados.vacaciones.pdf', ['empleado' => $empleado?->id, 'periodo_id' => $periodo->id])); ?>" target="_blank" class="btn-action-pdf" title="Descargar comprobante">
                                        <i class="fas fa-file-pdf"></i> PDF
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" style="text-align:center; padding: 40px 0; color: #5e7087;">
                                <i class="fas fa-folder-open" style="font-size: 2.5rem; color: #cbd5e1; margin-bottom: 15px; display: block;"></i>
                                No se encontraron registros de vacaciones en el sistema.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($periodosVacacionales->hasPages()): ?>
            <div class="pagination-container">
                <?php echo e($periodosVacacionales->links('pagination::bootstrap-4')); ?>

            </div>
        <?php endif; ?>
    </div>

    <style>
        .panel-principal-header {
            background: white; 
            padding: 24px 30px; 
            border-radius: 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
            text-align: center;
            margin-bottom: 25px;
            position: relative;
            overflow: hidden;
        }
        .panel-principal-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background-color: #a87e3b; 
        }
        .panel-principal-header h2 {
            margin: 0 0 8px 0;
            color: #2b0b4d; 
            font-size: 1.8rem;
            font-weight: 700;
        }
        .panel-principal-header p {
            margin: 0;
            color: #5e7087;
            font-size: 0.95rem;
        }

        .table-card-container {
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            margin-top: 25px;
            border: 1px solid #f1f5f9;
        }

        .table-card-header {
            background-color: #124416;
            color: white;
            padding: 18px 24px;
            font-size: 1.1rem;
            font-weight: 700;
            letter-spacing: 0.3px;
        }

        .search-form {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50px;
            padding: 4px;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .search-input {
            border: none;
            background: transparent;
            color: white;
            padding: 6px 12px;
            outline: none;
            width: 220px;
            font-size: 0.95rem;
        }
        .search-input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }
        .btn-search, .btn-clear-search {
            background: #a87e3b;
            color: white;
            border: none;
            border-radius: 50px;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: 0.2s;
            text-decoration: none;
        }
        .btn-search:hover { background: #916b30; }
        .btn-clear-search { background: #dc2626; margin-left: 5px; }
        .btn-clear-search:hover { background: #b91c1c; }

        .pagination-container {
            padding: 20px;
            display: flex;
            justify-content: flex-end;
            border-top: 1px solid #f8fafc;
            background-color: #ffffff;
        }
        .pagination-container nav ul {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
            gap: 5px;
        }
        .pagination-container nav ul li.page-item .page-link, 
        .pagination-container nav ul li.page-item span {
            padding: 8px 14px;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            color: #124416;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s;
            background-color: white;
        }
        .pagination-container nav ul li.page-item .page-link:hover {
            background-color: #f8fafc;
            border-color: #cbd5e1;
        }
        .pagination-container nav ul li.page-item.active span.page-link {
            background-color: #124416;
            color: white;
            border-color: #124416;
        }
        .pagination-container nav ul li.page-item.disabled span.page-link {
            color: #94a3b8;
            background-color: #f8fafc;
        }

        .responsive-table-v2 {
            width: 100%;
            border-collapse: collapse;
            font-family: system-ui, -apple-system, sans-serif;
            font-size: 0.95rem;
        }
        .responsive-table-v2 thead tr {
            background-color: #ffffff;
            border-bottom: 1px solid #f1f5f9;
        }
        .responsive-table-v2 th {
            padding: 16px 24px;
            color: #124416; 
            text-align: left;
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .responsive-table-v2 td {
            padding: 18px 24px;
            vertical-align: middle;
            border-bottom: 1px solid #f8fafc;
        }
        .responsive-table-v2 tbody tr:last-child td {
            border-bottom: none;
        }

        .text-employee-name { color: #334155; font-weight: 600; }
        .text-danger-bold { color: #ef4444; font-weight: 700; }
        .text-muted-days { color: #8293a6; font-weight: 700; }

        .badge {
            padding: 6px 12px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 500;
            display: inline-block;
        }
        .badge-success { background-color: #dcfce7; color: #15803d; }
        .badge-info { background-color: #e0f2fe; color: #0369a1; }

        .btn-action-edit, .btn-action-delete, .btn-action-pdf {
            border: none;
            cursor: pointer;
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: white;
            text-decoration: none;
        }

        .btn-action-edit { background-color: #124416; }
        .btn-action-edit:hover:not(:disabled) { background-color: #0d2e10; transform: translateY(-2px); }
        .btn-action-delete { background-color: #dc2626; }
        .btn-action-delete:hover { background-color: #b91c1c; transform: translateY(-2px); }
        .btn-action-pdf { background-color: #a87e3b; }
        .btn-action-pdf:hover { background-color: #8c6827; transform: translateY(-2px); }

        .btn-disabled {
            background-color: #cbd5e1 !important;
            color: #64748b !important;
            cursor: not-allowed !important;
            transform: none !important;
            box-shadow: none !important;
        }

        .modal-edit {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(3px);
        }
        .modal-edit.show { display: flex; }
        .modal-edit-content {
            background-color: white;
            padding: 30px;
            border-radius: 16px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            border: 1px solid #e2e8f0;
        }
        .modal-edit-content h3 { margin-top: 0; color: #124416; font-size: 1.4rem; font-weight: 700; margin-bottom: 20px; }
        .modal-edit-content .form-group { margin-bottom: 18px; }
        .modal-edit-content label { display: block; margin-bottom: 6px; color: #334155; font-weight: 600; font-size: 0.9rem; }
        .modal-edit-content input[type="text"],
        .modal-edit-content input[type="date"],
        .modal-edit-content textarea {
            width: 100%;
            padding: 11px 14px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 0.95rem;
            color: #334155;
            box-sizing: border-box;
            font-family: inherit;
            background-color: #f8fafc;
        }
        .modal-edit-content input[type="date"] {
            cursor: pointer;
            font-weight: 500;
        }
        .modal-edit-content input:focus,
        .modal-edit-content textarea:focus { 
            outline: none; 
            border-color: #124416; 
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(18, 68, 22, 0.15); 
        }
        .modal-edit-content textarea {
            resize: vertical;
            min-height: 80px;
        }
        .modal-edit-buttons { display: flex; gap: 12px; justify-content: flex-end; margin-top: 25px; }
        .modal-edit-buttons button {
            padding: 11px 22px;
            border: none;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-save { background-color: #124416; color: white; }
        .btn-save:hover { background-color: #0d2e10; }
        .btn-cancel { background-color: #f1f5f9; color: #475569; }
        .btn-cancel:hover { background-color: #cbd5e1; }
        .modal-edit-content #calendar-edit { margin-top: 10px; }
        .modal-edit-content .form-group textarea { min-height: 100px; }
    </style>

    <div id="editModal" class="modal-edit">
        <div class="modal-edit-content">
            <h3>Editar Período Vacacional</h3>
            <form id="editForm">
                <?php echo csrf_field(); ?>
                <div class="form-group">
                    <label for="editEmpleado">Empleado:</label>
                    <input type="text" id="editEmpleado" readonly>
                </div>
                <div class="form-group">
                    <label for="calendar-edit">Selecciona los días en el calendario</label>
                    <div id="calendar-edit"></div>
                    <input type="hidden" id="edit_multiple_dates" name="edit_multiple_dates" value="">
                    <input type="hidden" id="edit_fecha_inicio" name="edit_fecha_inicio" value="">
                    <input type="hidden" id="edit_fecha_fin" name="edit_fecha_fin" value="">
                    <input type="hidden" id="edit_dias" name="edit_dias" value="0">
                </div>
                <div class="form-group">
                    <label for="editObservaciones">Observaciones:</label>
                    <textarea id="editObservaciones" placeholder="Escribe aquí notas u observaciones internas..."></textarea>
                </div>
                <div class="modal-edit-buttons">
                    <button type="button" class="btn-cancel" onclick="closeEditModal()">Cancelar</button>
                    <button type="button" class="btn-save" onclick="guardarEdicion()">Guardar</button>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .flatpickr-calendar {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 40px rgba(15, 23, 42, 0.08);
    }
    .flatpickr-current-month .flatpickr-months {
        background-color: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        border-radius: 14px 14px 0 0;
    }
    .flatpickr-weekday {
        color: #334155;
        font-weight: 600;
    }
    .flatpickr-day.selected,
    .flatpickr-day.startRange,
    .flatpickr-day.endRange {
        background: #124416;
        color: white;
    }
    .flatpickr-day.today {
        border-color: #124416;
    }
    .flatpickr-day:hover {
        background: #d1fae5;
        color: #0f172a;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
<script>
let csrfTokenHist = "<?php echo e(csrf_token()); ?>";
let periodoEnEdicion = null;
let empleadoEnEdicion = null;
let fpEdit = null;

function initEditFlatpickr() {
    if (fpEdit) return;
    fpEdit = flatpickr("#calendar-edit", {
        inline: true,
        mode: "multiple",
        locale: "es",
        dateFormat: "Y-m-d",
        conjunction: ",",
        defaultDate: [],
        onChange: function(selectedDates, dateStr, instance) {
            const sortedDates = selectedDates.slice().sort((a, b) => a - b);
            const dateStrings = sortedDates.map(d => instance.formatDate(d, "Y-m-d"));
            document.getElementById('edit_multiple_dates').value = dateStrings.join(',');
            document.getElementById('edit_fecha_inicio').value = dateStrings.length ? dateStrings[0] : '';
            document.getElementById('edit_fecha_fin').value = dateStrings.length ? dateStrings[dateStrings.length - 1] : '';
            document.getElementById('edit_dias').value = dateStrings.length;
        }
    });
}

function openEditModal(id, empleadoId) {
    periodoEnEdicion = id;
    empleadoEnEdicion = empleadoId;

    Swal.fire({
        title: 'Cargando...',
        text: 'Obteniendo información del período',
        allowOutsideClick: false,
        allowEscapeKey: false,
        borderRadius: '25px',
        didOpen: () => Swal.showLoading()
    });

    fetch(`/periodos/${id}`, { credentials: 'same-origin' })
        .then(response => {
            if (!response.ok) throw new Error('Error fetching periodo');
            return response.json();
        })
        .then(data => {
            Swal.close();

            const fechaFinPeriodo = new Date(data.fecha_fin + 'T23:59:59');
            const hoy = new Date();
            if (fechaFinPeriodo < hoy) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Período finalizado',
                    text: 'Este período vacacional ya concluyó y no puede modificarse.',
                    confirmButtonColor: '#124416',
                    borderRadius: '25px'
                });
                periodoEnEdicion = null;
                empleadoEnEdicion = null;
                return;
            }

            document.getElementById('editEmpleado').value = data.empleado_nombre || 'N/A';
            if (!fpEdit) initEditFlatpickr();

            const start = new Date(data.fecha_inicio + 'T00:00:00');
            const end = new Date(data.fecha_fin + 'T00:00:00');
            const datesToSelect = [];
            for (let d = new Date(start); d <= end; d.setDate(d.getDate() + 1)) {
                datesToSelect.push(new Date(d));
            }
            fpEdit.setDate(datesToSelect);

            const initialSortedStrings = datesToSelect.map(d => d.toISOString().split('T')[0]);
            document.getElementById('edit_multiple_dates').value = initialSortedStrings.join(',');
            document.getElementById('edit_fecha_inicio').value = data.fecha_inicio;
            document.getElementById('edit_fecha_fin').value = data.fecha_fin;
            document.getElementById('edit_dias').value = initialSortedStrings.length;
            document.getElementById('editObservaciones').value = data.observaciones || '';
            document.getElementById('editModal').classList.add('show');
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo cargar el período.',
                confirmButtonColor: '#dc2626',
                borderRadius: '25px'
            });
        });
}

function closeEditModal() {
    document.getElementById('editModal').classList.remove('show');
    periodoEnEdicion = null;
    empleadoEnEdicion = null;
}

function guardarEdicion() {
    if (!periodoEnEdicion) return;

    const multipleDates = document.getElementById('edit_multiple_dates').value;
    const fechaInicio = document.getElementById('edit_fecha_inicio').value;
    const fechaFin = document.getElementById('edit_fecha_fin').value;
    const observaciones = document.getElementById('editObservaciones').value;

    if (!multipleDates || !fechaInicio || !fechaFin) {
        Swal.fire({
            icon: 'warning',
            title: 'Campos incompletos',
            text: 'Por favor selecciona al menos un día válido en el calendario.',
            confirmButtonColor: '#124416',
            borderRadius: '25px'
        });
        return;
    }

    const fechaInicioObj = new Date(fechaInicio + 'T00:00:00');
    const fechaFinObj = new Date(fechaFin + 'T23:59:59');

    if (fechaFinObj < fechaInicioObj) {
        Swal.fire({
            icon: 'warning',
            title: 'Fechas inválidas',
            text: 'La fecha de fin no puede ser anterior a la fecha de inicio.',
            confirmButtonColor: '#124416',
            borderRadius: '25px'
        });
        return;
    }

    if (fechaFinObj < new Date()) {
        Swal.fire({
            icon: 'warning',
            title: 'Período inválido',
            text: 'No puedes guardar un período con fechas que marquen el estatus como "Tomado".',
            confirmButtonColor: '#124416',
            borderRadius: '25px'
        });
        return;
    }

    Swal.fire({
        title: 'Guardando cambios...',
        text: 'Por favor espera',
        allowOutsideClick: false,
        allowEscapeKey: false,
        borderRadius: '25px',
        didOpen: () => Swal.showLoading()
    });

    fetch(`/periodos/${periodoEnEdicion}`, {
        method: 'PUT',
        credentials: 'same-origin',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfTokenHist,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            multiple_dates: multipleDates,
            fecha_inicio: fechaInicio,
            fecha_fin: fechaFin,
            observaciones: observaciones
        })
    })
    .then(async response => {
        const data = await response.json();
        if (!response.ok) {
            throw new Error(data.error || 'Error del servidor');
        }
        return data;
    })
    .then(() => {
        Swal.fire({
            icon: 'success',
            title: '¡Actualizado!',
            text: 'El período vacacional fue recalculado y modificado correctamente.',
            confirmButtonColor: '#124416',
            borderRadius: '25px'
        }).then(() => {
            closeEditModal();
            location.reload();
        });
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error al guardar',
            text: error.message,
            confirmButtonColor: '#dc2626',
            borderRadius: '25px'
        });
    });
}

function deletePeriodo(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción eliminará la solicitud y restaurará los días al balance del empleado.',
        icon: 'warning',
        borderRadius: '25px',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#e2e8f0',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: '<span style="color:#334155">Cancelar</span>'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Eliminando...',
                text: 'Por favor espera',
                allowOutsideClick: false,
                allowEscapeKey: false,
                borderRadius: '25px',
                didOpen: () => Swal.showLoading()
            });

            fetch(`/periodos/${id}`, {
                method: 'DELETE',
                credentials: 'same-origin',
                headers: {
                    'X-CSRF-TOKEN': csrfTokenHist,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(async response => {
                const data = await response.json();
                if (!response.ok) {
                    throw new Error(data.error || 'Error del servidor');
                }
                return data;
            })
            .then(() => {
                Swal.fire({
                    title: '¡Eliminado!',
                    text: 'Los días se han restaurado correctamente.',
                    icon: 'success',
                    confirmButtonColor: '#124416',
                    borderRadius: '25px'
                }).then(() => {
                    location.reload();
                });
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo eliminar: ' + error.message,
                    confirmButtonColor: '#dc2626',
                    borderRadius: '25px'
                });
            });
        }
    });
}

if (document.getElementById('editModal')) {
    document.getElementById('editModal').addEventListener('click', function(e) {
        if (e.target === this) closeEditModal();
    });
}

initEditFlatpickr();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/historial.blade.php ENDPATH**/ ?>