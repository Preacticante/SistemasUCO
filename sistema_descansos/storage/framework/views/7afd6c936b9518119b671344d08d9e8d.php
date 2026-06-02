

<?php $__env->startSection('title', 'Empleados'); ?>
<?php $__env->startSection('header', 'Directorio de Personal'); ?>


<?php $__env->startPush('styles'); ?>
<style>
    .employees-container {
        background: white;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        position: relative;
    }

    .employees-header {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        padding: 2rem;
        border-radius: 24px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.02);
        margin-bottom: 2rem;
        border: 1px solid rgba(255,255,255,0.8);
        border-bottom: 4px solid #AA7F31;
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
    }

    .employees-header h2 {
        margin: 0;
        color: #000000;
        font-size: 1.8rem;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .button-add-employee {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 1.5rem;
        gap: 1rem;
    }

    .btn-add {
        background-color: #124416;
        color: white;
        border: none;
        padding: 0.625rem 1.25rem;
        border-radius: 20px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 4px 10px rgba(18, 68, 22, 0.15);
        transition: all 0.3s ease;
    }

    .btn-add:hover {
        background-color: #0e3310;
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(18, 68, 22, 0.25);
    }

    .table-container {
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        margin: 0;
    }

    thead {
        background-color: #124416;
        border-bottom: 2px solid #e2e8f0;
    }

    th {
        padding: 0.875rem;
        color: #ffffff;
        font-weight: 600;
    }

    td {
        padding: 0.875rem;
        border-bottom: 1px solid #f1f5f9;
        transition: all 0.4s ease;
    }

    tr:hover td {
        background-color: #f8fafc;
    }

    /* Animación para el borrado suave */
    .row-fade-out td {
        opacity: 0 !important;
        padding-top: 0 !important;
        padding-bottom: 0 !important;
        height: 0 !important;
        line-height: 0 !important;
        transform: scaleY(0);
        border: none !important;
    }

    .td-id {
        font-weight: bold;
        color: #1e293b;
    }

    .td-nombre {
        color: #334155;
    }

    .td-puesto {
        color: #64748b;
    }

    .actions-cell {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .btn-action {
        border: none;
        padding: 0.375rem 0.875rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .btn-edit {
        background-color: #AA7F31;
        color: white;
    }

    .btn-edit:hover {
        background-color: #8c6827;
        transform: translateY(-1px);
    }

    .btn-vacations {
        background-color: #124416;
        color: white;
    }

    .btn-vacations:hover {
        background-color: #0e3310;
        transform: translateY(-1px);
    }

    .btn-delete {
        background-color: #b91c1c;
        color: white;
    }

    .btn-delete:hover {
        background-color: #991b1b;
        transform: translateY(-1px);
    }

    /* MODAL STYLES */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 1000;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(4px);
        padding: 1rem;
    }

    .modal.show {
        display: flex;
    }

    .modal-content {
        background: white;
        width: 100%;
        max-width: 500px;
        padding: 2rem;
        border-radius: 16px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        position: relative;
        max-height: 90vh;
        overflow-y: auto;
    }

    .modal-header {
        margin-top: 0;
        padding-bottom: 1rem;
        margin-bottom: 1.5rem;
        font-size: 1.5rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        border-bottom: 2px solid #AA7F31;
    }

    .modal-header.edit {
        color: #AA7F31;
        border-bottom-color: #124416;
    }

    .modal-header.add {
        color: #124416;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-group label {
        display: block;
        color: #334155;
        font-weight: 600;
        margin-bottom: 0.3rem;
        font-size: 0.9rem;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 0.625rem;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        box-sizing: border-box;
        outline: none;
        font-family: inherit;
        background: white;
        transition: border-color 0.3s ease;
    }

    .form-group input:focus,
    .form-group select:focus {
        border-color: #124416;
        box-shadow: 0 0 0 3px rgba(18, 68, 22, 0.1);
    }

    .modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
        margin-top: 1.5rem;
    }

    .btn-cancel {
        background: #e2e8f0;
        color: #475569;
        border: none;
        padding: 0.625rem 1.25rem;
        border-radius: 20px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-cancel:hover {
        background: #cbd5e1;
    }

    .btn-submit {
        background: #124416;
        color: white;
        border: none;
        padding: 0.625rem 1.25rem;
        border-radius: 20px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-submit:hover {
        background: #0e3310;
        transform: translateY(-1px);
    }

    .btn-submit.edit {
        background: #AA7F31;
    }

    .btn-submit.edit:hover {
        background: #8c6827;
    }

    @media (max-width: 768px) {
        .actions-cell { gap: 0.3rem; }
        .btn-action { padding: 0.3rem 0.6rem; font-size: 0.75rem; }
    }
    /* Estilos para la tabla */
.tabla-empleados {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    border-radius: 8px;
    overflow: hidden;
}

.tabla-empleados th {
    background-color: #1e4620; /* El color verde de tu barra actual */
    color: white;
    padding: 14px 16px;
    font-weight: 600;
    text-align: left;
}

.tabla-empleados td {
    padding: 12px 16px;
    border-bottom: 1px solid #eef2f5;
    color: #333;
    font-size: 14px;
}

.tabla-empleados tr:hover {
    background-color: #f8fafc;
}

/* Contenedor de botones en una sola línea */
.contenedor-acciones {
    display: flex;
    gap: 8px;
    justify-content: center;
    align-items: center;
}

/* Botones Base */
.btn-accion {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 12px;
    font-size: 13px;
    font-weight: 500;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.2s ease;
}

/* Botón Editar (Dorado/Marrón elegante) */
.btn-edit {
    background-color: #b58930;
    color: white;
}
.btn-edit:hover {
    background-color: #967126;
    box-shadow: 0 2px 4px rgba(181,137,48,0.3);
}

/* Botón Vacaciones (Verde institucional) */
.btn-vacaciones {
    background-color: #1e4620;
    color: white;
}
.btn-vacaciones:hover {
    background-color: #143015;
    box-shadow: 0 2px 4px rgba(30,70,32,0.3);
}

/* Botón Eliminar (Rojo controlado) */
.btn-eliminar {
    background-color: #dc3545;
    color: white;
}
.btn-eliminar:hover {
    background-color: #bd2130;
    box-shadow: 0 2px 4px rgba(220,53,69,0.3);
}

/* Efecto de desvanecimiento suave al eliminar filas con Soft Delete */
.row-fade-out {
    opacity: 0;
    transform: translateX(-20px);
    transition: all 0.5s ease;
}
</style>
<?php $__env->stopPush(); ?>


<?php $__env->startSection('content'); ?>
<div class="employees-container">
    <?php if(session('success')): ?>
        <div style="margin-bottom:1rem; padding:12px 16px; border-radius:8px; background:#ecfdf5; color:#065f46; font-weight:600;">
            <i class="fa-solid fa-circle-check"></i> <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div style="margin-bottom:1rem; padding:12px 16px; border-radius:8px; background:#fff1f2; color:#9f1239; font-weight:600;">
            <i class="fa-solid fa-triangle-exclamation"></i> <?php echo e($errors->first()); ?>

        </div>
    <?php endif; ?>
    <div class="employees-header">
        <h2>Control de Empleados</h2>
    </div>

    
    <?php
        $startYear = $empleados->count() ? $empleados->map(fn($e)=>\Carbon\Carbon::parse($e->fecha_ingreso)->year)->min() : now()->year;
        $endYear = now()->year;
        $selectedYear = request()->query('anio', $endYear);
    ?>
    <div style="display:flex; gap:1rem; align-items:center; margin:0.8rem 0;">
        <form method="GET" action="/empleados/vacaciones/pdf-masivo" target="_blank" style="display:flex; gap:0.5rem; align-items:center;">
            <select name="anio" style="padding:6px; border-radius:6px;">
                <?php for($y = $endYear; $y >= $startYear; $y--): ?>
                    <option value="<?php echo e($y); ?>" <?php if($y == $selectedYear): ?> selected <?php endif; ?>><?php echo e($y); ?></option>
                <?php endfor; ?>
            </select>
            <button type="submit" class="btn-add" style="padding:6px 10px;">Exportar Vacaciones (PDF)</button>
        </form>
    </div>

    <div class="button-add-employee">
        <button type="button" onclick="abrirModal()" class="btn-add">
            <i class="fa-solid fa-user-plus"></i> 
            <span class="btn-text">Agregar Empleado</span>
        </button>
    </div>

    <table class="tabla-empleados">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre Completo</th>
            <th>Puesto</th>
            <th style="text-align: center;">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $empleados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $empleado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr id="fila-empleado-<?php echo e($empleado->id); ?>">
                <td><?php echo e($empleado->id); ?></td>
                <td class="td-nombre">
                    <?php echo e($empleado->nombre); ?> <?php echo e($empleado->apellido_paterno); ?> <?php echo e($empleado->apellido_materno); ?>

                </td>
                <td class="td-puesto">
                    <?php echo e($empleado->puesto->nombre ?? 'Sin Puesto'); ?>

                </td>
                <td>
                    <div class="contenedor-acciones">
                        <button type="button" 
                                class="btn-accion btn-edit" 
                                onclick="abrirModalEditar(this)"
                                data-id="<?php echo e($empleado->id); ?>"
                                data-nombre="<?php echo e($empleado->nombre); ?>"
                                data-paterno="<?php echo e($empleado->apellido_paterno); ?>"
                                data-materno="<?php echo e($empleado->apellido_materno); ?>"
                                data-puesto="<?php echo e($empleado->puesto_id); ?>"
                                data-fecha="<?php echo e($empleado->fecha_ingreso); ?>">
                            <i class="fa-solid fa-pen-to-square"></i> Editar
                        </button>

                        <a href="#" class="btn-accion btn-vacaciones">
                            <i class="fa-solid fa-calendar-days"></i> Vacaciones
                        </a>

                        <button type="button" 
                                class="btn-accion btn-eliminar" 
                                onclick="confirmarSoftDelete(<?php echo e($empleado->id); ?>, '<?php echo e(route('empleados.destroy', $empleado->id)); ?>')">
                            <i class="fa-solid fa-trash"></i> Eliminar
                        </button>
                    </div>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
    </div>
</div>

<div id="modalAgregar" class="modal">
    <div class="modal-content">
        <h3 class="modal-header add">
            <i class="fa-solid fa-user-plus"></i> Nuevo Empleado
        </h3>
        <form action="<?php echo e(route('empleados.store')); ?>" method="POST">
            <?php echo csrf_field(); ?> 
            <div class="form-group">
                <label>Nombre(s)</label>
                <input type="text" name="nombre" required>
            </div>
            <div class="form-group">
                <label>Apellido Paterno</label>
                <input type="text" name="apellido_paterno" required>
            </div>
            <div class="form-group">
                <label>Apellido Materno</label>
                <input type="text" name="apellido_materno">
            </div>
            <div class="form-group">
                <label>Puesto</label>
                <select name="puesto_id" required>
                    <option value="" disabled selected>Selecciona un puesto...</option>
                    <?php $__currentLoopData = $puestos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $puesto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($puesto->id); ?>"><?php echo e($puesto->nombre); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="form-group">
                <label>Fecha de Ingreso</label>
                <input type="date" name="fecha_ingreso" required>
            </div>
            <div class="modal-actions">
                <button type="button" onclick="cerrarModal()" class="btn-cancel">Cancelar</button>
                <button type="submit" class="btn-submit">Guardar</button>
            </div>
        </form>
    </div>
</div>

<div id="modalEditar" class="modal">
    <div class="modal-content">
        <h3 class="modal-header edit">
            <i class="fa-solid fa-user-pen"></i> Editar Empleado
        </h3>
        <form id="formEditar" onsubmit="actualizarEmpleado(event)">
            <?php echo csrf_field(); ?> 
            <?php echo method_field('PUT'); ?>
            
            <input type="hidden" id="edit_id" name="id">

            <div class="form-group">
                <label>Nombre(s)</label>
                <input type="text" name="nombre" id="edit_nombre" required>
            </div>
            <div class="form-group">
                <label>Apellido Paterno</label>
                <input type="text" name="apellido_paterno" id="edit_paterno" required>
            </div>
            <div class="form-group">
                <label>Apellido Materno</label>
                <input type="text" name="apellido_materno" id="edit_materno">
            </div>
            <div class="form-group">
                <label>Puesto</label>
                <select name="puesto_id" id="edit_puesto_id" required>
                    <option value="" disabled>Selecciona un puesto...</option>
                    <?php $__currentLoopData = $puestos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $puesto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($puesto->id); ?>"><?php echo e($puesto->nombre); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="form-group">
                <label>Fecha de Ingreso</label>
                <input type="date" name="fecha_ingreso" id="edit_fecha" required>
            </div>
            <div class="modal-actions">
                <button type="button" onclick="cerrarModalEditar()" class="btn-cancel">Cancelar</button>
                <button type="submit" class="btn-submit edit">Actualizar</button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php $__env->startPush('scripts'); ?>
<script>
    const csrfToken = '<?php echo e(csrf_token()); ?>';

    // --- MODAL DE AGREGAR ---
    function abrirModal() {
        document.getElementById('modalAgregar').classList.add('show');
    }
    function cerrarModal() {
        document.getElementById('modalAgregar').classList.remove('show');
    }

    // --- MODAL DE EDICIÓN (LECTURA ULTRA ESTABLE) ---
    function abrirModalEditar(boton) {
        try {
            // Extraemos los datos directamente de los atributos data- del botón que fue presionado
            const id = boton.getAttribute('data-id');
            const nombre = boton.getAttribute('data-nombre');
            const paterno = boton.getAttribute('data-paterno');
            const materno = boton.getAttribute('data-materno') || '';
            const puestoId = boton.getAttribute('data-puesto');
            let fecha = boton.getAttribute('data-fecha') || '';

            // Limpiamos la fecha por si viene con horas desde la base de datos
            if (fecha) {
                fecha = fecha.split(' ')[0];
            }

            // Inyectamos los valores a los inputs del modal de edición
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_nombre').value = nombre;
            document.getElementById('edit_paterno').value = paterno;
            document.getElementById('edit_materno').value = materno;
            document.getElementById('edit_puesto_id').value = puestoId;
            document.getElementById('edit_fecha').value = fecha;

            // Abrimos el modal añadiendo la clase show
            document.getElementById('modalEditar').classList.add('show');
        } catch (error) {
            console.error("Error al abrir el modal:", error);
            alert("No se pudieron cargar los datos en el formulario.");
        }
    }

    function cerrarModalEditar() {
        document.getElementById('modalEditar').classList.remove('show');
    }

    // --- GUARDAR CAMBIOS EN LA BASE DE DATOS ---
    function actualizarEmpleado(event) {
        event.preventDefault();

        const form = document.getElementById('formEditar');
        const id = document.getElementById('edit_id').value;
        const formData = new FormData(form);

        fetch(`/empleados/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en el servidor');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Actualizamos visualmente la tabla
                const fila = document.getElementById(`fila-empleado-${id}`);
                if (fila) {
                    const selectPuesto = document.getElementById('edit_puesto_id');
                    const textoPuesto = selectPuesto.options[selectPuesto.selectedIndex].text;

                    const nombreCompleto = `${formData.get('nombre')} ${formData.get('apellido_paterno')} ${formData.get('apellido_materno')}`;
                    
                    // Reemplazamos los textos de la fila en tiempo real
                    fila.querySelector('.td-nombre').textContent = nombreCompleto;
                    fila.querySelector('.td-puesto').textContent = textoPuesto;
                    
                    // Actualizamos también los atributos data- del botón por si lo vuelven a editar sin recargar
                    const btnEditar = fila.querySelector('.btn-edit');
                    if (btnEditar) {
                        btnEditar.setAttribute('data-nombre', formData.get('nombre'));
                        btnEditar.setAttribute('data-paterno', formData.get('apellido_paterno'));
                        btnEditar.setAttribute('data-materno', formData.get('apellido_materno'));
                        btnEditar.setAttribute('data-puesto', formData.get('puesto_id'));
                        btnEditar.setAttribute('data-fecha', formData.get('fecha_ingreso'));
                    }
                }

                cerrarModalEditar();
                alert('Empleado guardado correctamente en la Base de Datos.');
            } else {
                alert('No se pudieron guardar los cambios: ' + (data.message || 'Error interno'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error de comunicación con el servidor.');
        });
    }

    // --- SOFT DELETE REAL ---
    function confirmarSoftDelete(id, urlRoute) {
        if (!confirm('¿Realmente deseas eliminar a este empleado?')) return;

        fetch(urlRoute, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ _method: 'DELETE' })
        })
        .then(response => {
            if (response.ok) {
                const fila = document.getElementById('fila-empleado-' + id);
                if (fila) {
                    fila.classList.add('row-fade-out');
                    setTimeout(() => { fila.remove(); }, 500);
                }
            } else {
                alert('Error al eliminar en el servidor.');
            }
        })
        .catch(error => alert('Error de red.'));
    }

    // Evento para cerrar haciendo clic afuera de los modales
    window.addEventListener('click', function(event) {
        const modalAgregar = document.getElementById('modalAgregar');
        const modalEditar = document.getElementById('modalEditar');
        if (event.target === modalAgregar) cerrarModal();
        if (event.target === modalEditar) cerrarModalEditar();
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\becario.tie\Documents\GitHub\SistemasUCO\sistema_descansos\resources\views/empleados/index.blade.php ENDPATH**/ ?>