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
                                        <i class="fas fa-pencil"></i> Editar
                                    </button>
                                    <button type="button" class="btn-action-delete" onclick="deletePeriodo(<?php echo e($periodo->id); ?>)">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                    <a href="/empleados/<?php echo e($empleado?->id); ?>/vacaciones/pdf" target="_blank" class="btn-action-pdf" title="Descargar comprobante">
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
        /* Contenedor de la cabecera superior */
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

        /* Envoltorio de la tabla */
        .table-card-container {
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            margin-top: 25px;
            border: 1px solid #f1f5f9;
        }

        /* Banner de encabezado morado en la tabla */
        .table-card-header {
            background-color: #124416;
            color: white;
            padding: 18px 24px;
            font-size: 1.1rem;
            font-weight: 700;
            letter-spacing: 0.3px;
        }

        /* BUSCADOR ESTILOS */
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

        /* PAGINACIÓN ESTILOS */
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

        /* Estructura general de la tabla */
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

        /* Badges e indicadores visuales de estado */
        .badge {
            padding: 6px 12px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 500;
            display: inline-block;
        }
        .badge-success { background-color: #dcfce7; color: #15803d; }
        .badge-info { background-color: #e0f2fe; color: #0369a1; }

        /* Estilos para botones de acciones */
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

        /* Modal de edición */
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
        }
        .modal-edit.show { display: flex; }
        .modal-edit-content {
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }
        .modal-edit-content h3 { margin-top: 0; color: #124416; font-size: 1.3rem; }
        .modal-edit-content .form-group { margin-bottom: 15px; }
        .modal-edit-content label { display: block; margin-bottom: 5px; color: #334155; font-weight: 600; font-size: 0.9rem; }
        .modal-edit-content input {
            width: 100%;
            padding: 10px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 0.95rem;
            box-sizing: border-box;
        }
        .modal-edit-content input:focus { outline: none; border-color: #124416; box-shadow: 0 0 0 3px rgba(18, 68, 22, 0.1); }
        .modal-edit-buttons { display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px; }
        .modal-edit-buttons button {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-save { background-color: #124416; color: white; }
        .btn-save:hover { background-color: #0d2e10; }
        .btn-cancel { background-color: #e2e8f0; color: #334155; }
        .btn-cancel:hover { background-color: #cbd5e1; }
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
                    <label for="editFechaInicio">Fecha Inicio:</label>
                    <input type="date" id="editFechaInicio" required>
                </div>
                <div class="form-group">
                    <label for="editFechaFin">Fecha Fin:</label>
                    <input type="date" id="editFechaFin" required>
                </div>
                <div class="modal-edit-buttons">
                    <button type="button" class="btn-cancel" onclick="closeEditModal()">Cancelar</button>
                    <button type="button" class="btn-save" onclick="guardarEdicion()">Guardar</button>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    let csrfTokenHist = "<?php echo e(csrf_token()); ?>";
    let periodoEnEdicion = null;
    let empleadoEnEdicion = null; 

    function openEditModal(id, empleadoId) {
        periodoEnEdicion = id;
        empleadoEnEdicion = empleadoId; 
        
        fetch(`/periodos/${id}`)
            .then(response => {
                if (!response.ok) throw new Error('Error fetching periodo');
                return response.json();
            })
            .then(data => {
                const fechaFinPeriodo = new Date(data.fecha_fin + 'T23:59:59');
                const hoy = new Date();

                if (fechaFinPeriodo < hoy) {
                    alert('Este período vacacional ya concluyó y no puede modificarse.');
                    periodoEnEdicion = null;
                    empleadoEnEdicion = null;
                    return;
                }

                document.getElementById('editEmpleado').value = data.empleado_nombre || 'N/A';
                document.getElementById('editFechaInicio').value = data.fecha_inicio;
                document.getElementById('editFechaFin').value = data.fecha_fin;
                document.getElementById('editModal').classList.add('show');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('No se pudo cargar el período');
            });
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.remove('show');
        periodoEnEdicion = null;
        empleadoEnEdicion = null;
    }

    function guardarEdicion() {
        if (!periodoEnEdicion) return;

        const fechaInicio = document.getElementById('editFechaInicio').value;
        const fechaFin = document.getElementById('editFechaFin').value;

        // VALIDACIÓN FRONTEND: Que las fechas estén llenas
        if (!fechaInicio || !fechaFin) {
            alert('Por favor completa las fechas de inicio y fin.');
            return;
        }

        const fechaInicioObj = new Date(fechaInicio + 'T00:00:00');
        const fechaFinObj = new Date(fechaFin + 'T23:59:59');
        
        // VALIDACIÓN FRONTEND: Fecha lógica
        if (fechaFinObj < fechaInicioObj) {
            alert('La fecha de fin no puede ser anterior a la fecha de inicio.');
            return;
        }

        if (fechaFinObj < new Date()) {
            alert('No puedes guardar un período con fechas que marquen el estatus como "Tomado".');
            return;
        }

        // Fíjate que ya NO mandamos la variable "dias", el servidor lo hará por nosotros
        fetch(`/periodos/${periodoEnEdicion}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfTokenHist
            },
            body: JSON.stringify({
                fecha_inicio: fechaInicio,
                fecha_fin: fechaFin
            })
        })
        .then(async response => {
            const data = await response.json();
            if (!response.ok) throw new Error(data.error || 'Error del servidor');
            return data;
        })
        .then(data => {
            alert('¡Período vacacional recalculado y modificado correctamente!');
            closeEditModal();
            location.reload(); 
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al guardar: ' + error.message);
        });
    }

    function deletePeriodo(id) {
        if (!confirm('¿Estás seguro de que deseas eliminar este período vacacional y restaurar los días al empleado?')) {
            return;
        }

        fetch(`/periodos/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfTokenHist
            }
        })
        .then(async response => {
            const data = await response.json();
            if (!response.ok) throw new Error(data.error || 'Error del servidor');
            return data;
        })
        .then(data => {
            alert('Período eliminado correctamente. Los días se han restaurado.');
            location.reload();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al eliminar: ' + error.message);
        });
    }

    document.getElementById('editModal').addEventListener('click', function(e) {
        if (e.target === this) closeEditModal();
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\becario.tie\Documents\GitHub\SistemasUCO\sistema_descansos\resources\views/historial.blade.php ENDPATH**/ ?>