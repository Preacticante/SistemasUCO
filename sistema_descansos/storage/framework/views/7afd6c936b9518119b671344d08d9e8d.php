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
    }

    tr:hover td {
        background-color: #f8fafc;
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

    /* RESPONSIVO */
    @media (max-width: 1024px) {
        .employees-container { padding: 1.5rem; }
        .employees-header { padding: 1.5rem; }
        .employees-header h2 { font-size: 1.5rem; }
    }

    @media (max-width: 768px) {
        .employees-container { padding: 1rem; }
        .employees-header { padding: 1.2rem; margin-bottom: 1.5rem; }
        .employees-header h2 { font-size: 1.3rem; }
        .button-add-employee { margin-bottom: 1rem; }
        .btn-add { width: 100%; justify-content: center; padding: 0.7rem 1rem; }
        table { font-size: 0.9rem; }
        th, td { padding: 0.7rem; }
        .actions-cell { gap: 0.3rem; }
        .btn-action { padding: 0.3rem 0.6rem; font-size: 0.75rem; }
        .btn-action i { font-size: 0.8rem; }
        .modal-content { padding: 1.5rem; }
        .modal-header { font-size: 1.2rem; }
        .modal-actions { flex-direction: column; }
        .btn-cancel, .btn-submit { width: 100%; }
    }

    @media (max-width: 480px) {
        .employees-container { padding: 0.75rem; }
        .employees-header { padding: 1rem; margin-bottom: 1rem; border-radius: 16px; }
        .employees-header h2 { font-size: 1.1rem; letter-spacing: 0; }
        .button-add-employee { margin-bottom: 0.75rem; }
        .table-container { border-radius: 8px; }
        table { font-size: 0.8rem; }
        th, td { padding: 0.5rem; }
        .td-id { max-width: 30px; overflow: hidden; text-overflow: ellipsis; }
        .actions-cell { flex-direction: column; gap: 0.2rem; }
        .btn-action { width: 100%; justify-content: center; padding: 0.5rem; font-size: 0.7rem; }
        .modal-content { padding: 1.2rem; max-height: calc(100vh - 2rem); }
        .modal-header { font-size: 1rem; gap: 0.3rem; }
        .form-group { margin-bottom: 0.75rem; }
        .form-group label { font-size: 0.8rem; margin-bottom: 0.2rem; }
        .form-group input, .form-group select { padding: 0.5rem; font-size: 0.9rem; }
        .modal-actions { gap: 0.5rem; }
        .btn-cancel, .btn-submit { padding: 0.5rem; font-size: 0.8rem; }
    }
</style>
<?php $__env->stopPush(); ?>


<?php $__env->startSection('content'); ?>
<div class="employees-container">
    <div class="employees-header">
        <h2>Control de Empleados</h2>
    </div>

    <div class="button-add-employee">
        <button onclick="abrirModal()" class="btn-add">
            <i class="fa-solid fa-user-plus"></i> 
            <span class="btn-text">Agregar Empleado</span>
        </button>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre Completo</th>
                    <th>Puesto</th>
                    <th style="text-align: center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $empleados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr id="fila-empleado-<?php echo e($emp->id); ?>" style="transition: all 0.4s ease;">
                    <td class="td-id"><?php echo e($emp->id); ?></td>
                    <td class="td-nombre"><?php echo e($emp->nombre); ?> <?php echo e($emp->apellido_paterno); ?> <?php echo e($emp->apellido_materno); ?></td>
                    <td class="td-puesto"><?php echo e($emp->puesto->nombre ?? 'Sin Puesto'); ?></td>
                    <td>
                        <div class="actions-cell">
                            <button onclick="abrirModalEditar(<?php echo e(json_encode($emp)); ?>)" class="btn-action btn-edit">
                                <i class="fa-solid fa-pen-to-square"></i> 
                                <span class="btn-text">Editar</span>
                            </button>

                            <a href="<?php echo e(route('empleados.vacaciones', $emp->id)); ?>" class="btn-action btn-vacations">
                                <i class="fa-solid fa-calendar"></i> 
                                <span class="btn-text">Vacaciones</span>
                            </a>

                            <button onclick="eliminarFilaVisual(<?php echo e($emp->id); ?>)" class="btn-action btn-delete">
                                <i class="fa-solid fa-trash"></i> 
                                <span class="btn-text">Eliminar</span>
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
        <form id="formEditar" method="POST">
            <?php echo csrf_field(); ?> 
            <?php echo method_field('PUT'); ?>
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
    function abrirModal() {
        document.getElementById('modalAgregar').classList.add('show');
    }

    function cerrarModal() {
        document.getElementById('modalAgregar').classList.remove('show');
    }

    function abrirModalEditar(empleado) {
        const form = document.getElementById('formEditar');
        form.action = `/empleados/${empleado.id}`;

        document.getElementById('edit_nombre').value = empleado.nombre;
        document.getElementById('edit_paterno').value = empleado.apellido_paterno;
        document.getElementById('edit_materno').value = empleado.apellido_materno || '';
        document.getElementById('edit_puesto_id').value = empleado.puesto_id;
        document.getElementById('edit_fecha').value = empleado.fecha_ingreso;

        document.getElementById('modalEditar').classList.add('show');
    }

    function cerrarModalEditar() {
        document.getElementById('modalEditar').classList.remove('show');
    }

    function eliminarFilaVisual(id) {
        if (confirm('¿Eliminar de la vista?')) {
            const fila = document.getElementById('fila-empleado-' + id);
            if (fila) {
                fila.style.opacity = '0';
                setTimeout(() => fila.style.display = 'none', 300);
            }
        }
    }

    // Cerrar modales al hacer clic fuera de ellos
    window.onclick = function(event) {
        const modalAgregar = document.getElementById('modalAgregar');
        const modalEditar = document.getElementById('modalEditar');
        
        if (event.target == modalAgregar) cerrarModal();
        if (event.target == modalEditar) cerrarModalEditar();
    }
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\becario.tie\Documents\GitHub\SistemasUCO\sistema_descansos\resources\views/empleados/index.blade.php ENDPATH**/ ?>