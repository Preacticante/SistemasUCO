<?php $__env->startSection('title', 'Empleados'); ?>
<?php $__env->startSection('header', 'Directorio de Personal'); ?>

<?php $__env->startSection('content'); ?>
<div style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); position: relative;">
    
    <div style="background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); padding: 25px 40px; border-radius: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.02); margin-bottom: 20px; border: 1px solid rgba(255,255,255,0.8); border-bottom: 4px solid #AA7F31; display: flex; justify-content: center; align-items: center;">
        <h2 style="margin: 0; color: #000000; font-size: 1.8rem; font-weight: 700; letter-spacing: 0.5px;">
            Control de Empleados
        </h2>
    </div>

    <div style="display: flex; justify-content: flex-end; margin-bottom: 15px;">
        <button onclick="abrirModal()" style="background-color: #124416; color: white; border: none; padding: 10px 20px; border-radius: 20px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 10px rgba(18, 68, 22, 0.15);">
            <i class="fa-solid fa-user-plus"></i> Agregar Empleado
        </button>
    </div>

    <div style="border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; margin: 0;">
            <thead>
                <tr style="background-color: #124416; border-bottom: 2px solid #e2e8f0;">
                    <th style="padding: 14px; color: #ffffff;">ID</th>
                    <th style="padding: 14px; color: #ffffff;">Nombre Completo</th>
                    <th style="padding: 14px; color: #ffffff;">Puesto</th>
                    <th style="padding: 14px; color: #ffffff; text-align: center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $empleados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr id="fila-empleado-<?php echo e($emp->id); ?>" style="border-bottom: 1px solid #f1f5f9; transition: all 0.4s ease;">
                    <td style="padding: 14px; font-weight: bold; color: #1e293b;"><?php echo e($emp->id); ?></td>
                    <td style="padding: 14px; color: #334155;"><?php echo e($emp->nombre); ?> <?php echo e($emp->apellido_paterno); ?> <?php echo e($emp->apellido_materno); ?></td>
                    <td style="padding: 14px; color: #64748b;"><?php echo e($emp->puesto->nombre ?? 'Sin Puesto'); ?></td>
                    <td style="padding: 14px; text-align: center; display: flex; justify-content: center; gap: 8px;">
                        
                        <button onclick="abrirModalEditar(<?php echo e(json_encode($emp)); ?>)" style="background-color: #AA7F31; color: white; border: none; padding: 6px 14px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 5px;">
                            <i class="fa-solid fa-pen-to-square"></i> Editar
                        </button>

                        <a href="<?php echo e(route('empleados.vacaciones', $emp->id)); ?>" style="background-color: #124416; color: white; text-decoration: none; padding: 6px 14px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 5px;">
                            <i class="fa-solid fa-calendar"></i> Vacaciones
                        </a>

                        <button onclick="eliminarFilaVisual(<?php echo e($emp->id); ?>)" style="background-color: #b91c1c; color: white; border: none; padding: 6px 14px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 5px;">
                            <i class="fa-solid fa-trash"></i> Eliminar
                        </button>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>

<div id="modalAgregar" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
    <div style="background: white; width: 100%; max-width: 500px; padding: 30px; border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); position: relative; margin: 20px;">
        <h3 style="margin-top: 0; color: #124416; border-bottom: 2px solid #AA7F31; padding-bottom: 10px; margin-bottom: 20px; font-size: 1.5rem;">
            <i class="fa-solid fa-user-plus"></i> Nuevo Empleado
        </h3>
        <form action="<?php echo e(route('empleados.store')); ?>" method="POST">
            <?php echo csrf_field(); ?> 
            <div style="margin-bottom: 15px;">
                <label style="display: block; color: #334155; font-weight: 600; margin-bottom: 5px;">Nombre(s)</label>
                <input type="text" name="nombre" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; box-sizing: border-box; outline: none;">
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display: block; color: #334155; font-weight: 600; margin-bottom: 5px;">Apellido Paterno</label>
                <input type="text" name="apellido_paterno" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; box-sizing: border-box; outline: none;">
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display: block; color: #334155; font-weight: 600; margin-bottom: 5px;">Apellido Materno</label>
                <input type="text" name="apellido_materno" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; box-sizing: border-box; outline: none;">
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display: block; color: #334155; font-weight: 600; margin-bottom: 5px;">Puesto</label>
                <div style="margin-bottom: 15px;">
    <label style="display: block; color: #334155; font-weight: 600; margin-bottom: 5px;">Puesto</label>
    <input type="text" name="puesto" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; box-sizing: border-box; outline: none;">
</div>

<div style="margin-bottom: 15px;">
    <label style="display: block; color: #334155; font-weight: 600; margin-bottom: 5px;">Puesto</label>
    <select name="puesto_id" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; box-sizing: border-box; outline: none; background: white; font-family: inherit;">
        <option value="" disabled selected>Selecciona un puesto...</option>
        <?php $__currentLoopData = $puestos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $puesto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($puesto->id); ?>"><?php echo e($puesto->nombre); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
</div>
            </div>
            <div style="margin-bottom: 25px;">
                <label style="display: block; color: #334155; font-weight: 600; margin-bottom: 5px;">Fecha de Ingreso</label>
                <input type="date" name="fecha_ingreso" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; box-sizing: border-box; outline: none;">
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="cerrarModal()" style="background: #e2e8f0; color: #475569; border: none; padding: 10px 20px; border-radius: 20px; font-weight: 600; cursor: pointer;">Cancelar</button>
                <button type="submit" style="background: #124416; color: white; border: none; padding: 10px 20px; border-radius: 20px; font-weight: 600; cursor: pointer;">Guardar</button>
            </div>
        </form>
    </div>
</div>

<div id="modalEditar" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
    <div style="background: white; width: 100%; max-width: 500px; padding: 30px; border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); position: relative; margin: 20px;">
        <h3 style="margin-top: 0; color: #AA7F31; border-bottom: 2px solid #124416; padding-bottom: 10px; margin-bottom: 20px; font-size: 1.5rem;">
            <i class="fa-solid fa-user-pen"></i> Editar Empleado
        </h3>
        <form id="formEditar" method="POST">
            <?php echo csrf_field(); ?> 
            <?php echo method_field('PUT'); ?> <div style="margin-bottom: 15px;">
                <label style="display: block; color: #334155; font-weight: 600; margin-bottom: 5px;">Nombre(s)</label>
                <input type="text" name="nombre" id="edit_nombre" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; box-sizing: border-box; outline: none;">
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display: block; color: #334155; font-weight: 600; margin-bottom: 5px;">Apellido Paterno</label>
                <input type="text" name="apellido_paterno" id="edit_paterno" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; box-sizing: border-box; outline: none;">
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display: block; color: #334155; font-weight: 600; margin-bottom: 5px;">Apellido Materno</label>
                <input type="text" name="apellido_materno" id="edit_materno" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; box-sizing: border-box; outline: none;">
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display: block; color: #334155; font-weight: 600; margin-bottom: 5px;">Puesto</label>
                <div style="margin-bottom: 15px;">
    <label style="display: block; color: #334155; font-weight: 600; margin-bottom: 5px;">Puesto</label>
    <input type="text" name="puesto" id="edit_puesto" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; box-sizing: border-box; outline: none;">
</div>

<div style="margin-bottom: 15px;">
    <label style="display: block; color: #334155; font-weight: 600; margin-bottom: 5px;">Puesto</label>
    <select name="puesto_id" id="edit_puesto" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; box-sizing: border-box; outline: none; background: white; font-family: inherit;">
        <option value="" disabled>Selecciona un puesto...</option>
        <?php $__currentLoopData = $puestos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $puesto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($puesto->id); ?>"><?php echo e($puesto->nombre); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
</div>
            </div>
            <div style="margin-bottom: 25px;">
                <label style="display: block; color: #334155; font-weight: 600; margin-bottom: 5px;">Fecha de Ingreso</label>
                <input type="date" name="fecha_ingreso" id="edit_fecha" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; box-sizing: border-box; outline: none;">
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="cerrarModalEditar()" style="background: #e2e8f0; color: #475569; border: none; padding: 10px 20px; border-radius: 20px; font-weight: 600; cursor: pointer;">Cancelar</button>
                <button type="submit" style="background: #AA7F31; color: white; border: none; padding: 10px 20px; border-radius: 20px; font-weight: 600; cursor: pointer;">Actualizar Cambios</button>
            </div>
        </form>
    </div>
</div>

<script>
    // MODAL AGREGAR
    function abrirModal() {
        document.getElementById('modalAgregar').style.display = 'flex';
    }
    function cerrarModal() {
        document.getElementById('modalAgregar').style.display = 'none';
    }

    // MODAL EDITAR (Lógica principal)
    function abrirModalEditar(empleado) {
        // 1. Cambiamos la URL del formulario dinámicamente
        const form = document.getElementById('formEditar');
        form.action = `/empleados/${empleado.id}`;

        // 2. Llenamos los inputs con los datos del empleado
        document.getElementById('edit_nombre').value = empleado.nombre;
        document.getElementById('edit_paterno').value = empleado.apellido_paterno;
        document.getElementById('edit_materno').value = empleado.apellido_materno || '';
        document.getElementById('edit_puesto').value = empleado.puesto_id;
        document.getElementById('edit_fecha').value = empleado.fecha_ingreso;document.getElementById('edit_puesto').value = empleado.puesto;

        // 3. Mostramos el modal
        document.getElementById('modalEditar').style.display = 'flex';
    }

    function cerrarModalEditar() {
        document.getElementById('modalEditar').style.display = 'none';
    }

    // ELIMINADO VISUAL
    function eliminarFilaVisual(id) {
        if (confirm('¿Eliminar de la vista?')) {
            const fila = document.getElementById('fila-empleado-' + id);
            if (fila) {
                fila.style.opacity = '0';
                setTimeout(() => fila.style.display = 'none', 300);
            }
        }
    }

    // Cerrar modales al hacer clic fuera
    window.onclick = function(event) {
        if (event.target == document.getElementById('modalAgregar')) cerrarModal();
        if (event.target == document.getElementById('modalEditar')) cerrarModalEditar();
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\becario.tie\Documents\GitHub\SistemasUCO\sistema_descansos\resources\views/empleados/index.blade.php ENDPATH**/ ?>