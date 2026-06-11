<?php $__env->startSection('title', 'Usuarios'); ?>
<?php $__env->startSection('header', 'Gestión de Usuarios'); ?>

<?php $__env->startSection('content'); ?>
<div class="profiles-container">
    <div class="profiles-header">
        <h2>Usuarios del Sistema</h2>
        <p>Registra y administra las cuentas de acceso con folios institucionales UCO.</p>
    </div>

    <div class="profiles-layout">
        <div class="profiles-list-card">
            <div class="card-header">
                <i class="fa-solid fa-users"></i> Cuentas Activas
            </div>
            <div id="lista-usuarios" class="list-body">
                <div class="empty">Cargando usuarios...</div>
            </div>
        </div>

        <div class="profiles-form-card">
            <div class="card-header">
                <i class="fa-solid fa-user-plus"></i> Nuevo / Editar Usuario
            </div>
            <form id="formUsuario" class="card-body" autocomplete="off">
                <?php echo csrf_field(); ?>
                <input type="hidden" id="usuario_id" name="usuario_id" value="">
                
                <div class="form-group">
                    <label for="nombre_completo">Nombre Completo</label>
                    <input type="text" id="nombre_completo" name="nombre_completo" placeholder="Ej. Lic. Angelica" required>
                </div>

                <div class="form-group">
                    <label for="correo">Correo Electrónico</label>
                    <input type="email" id="correo" name="correo" placeholder="Ej. admin@sistema.com" required>
                </div>

                <div class="form-group">
                    <label for="departamento">Departamento</label>
                    <input type="text" id="departamento" name="departamento" placeholder="Ej. Administración Educativa" required>
                </div>
                
                <div class="form-group">
                    <label for="contrasena">Contraseña de Acceso</label>
                    <input type="password" id="contrasena" name="contrasena" placeholder="Escribe la contraseña">
                    <small style="color: #64748b; font-size: 0.8rem; display: block; margin-top: 4px;" id="pass-help">
                        *Para usuarios existentes, dejar en blanco si no se desea cambiar.
                    </small>
                </div>
                
                <div class="modal-actions">
                    <button type="button" id="cancelUsuario" class="btn-cancel">
                        <i class="fa-solid fa-xmark"></i> Cancelar
                    </button>
                    <button type="submit" class="btn-submit">
                        <i class="fa-solid fa-floppy-disk"></i> Guardar Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700&display=swap');

.profiles-container {
    font-family: 'Nunito Sans', sans-serif;
    color: #334155;
    padding: 10px;
}

.profiles-header { 
    background: white; 
    padding: 24px; 
    border-radius: 16px; 
    margin-bottom: 24px; 
    box-shadow: 0 4px 20px rgba(0,0,0,0.03); 
    border-bottom: 4px solid #b38e36; 
}
.profiles-header h2 { 
    margin: 0 0 6px 0; 
    color: #11431c; 
    font-weight: 700;
    font-size: 1.6rem;
}
.profiles-header p {
    margin: 0;
    color: #64748b;
    font-size: 0.95rem;
}

.profiles-layout { 
    display: flex; 
    gap: 24px; 
    align-items: flex-start; 
}
.profiles-list-card, .profiles-form-card { 
    background: white; 
    border-radius: 16px; 
    box-shadow: 0 10px 25px rgba(0,0,0,0.03); 
    flex: 1; 
    overflow: hidden;
    border-bottom: 4px solid #b38e36;
}

.card-header { 
    background-color: #11431c; 
    color: #ffffff;
    font-weight: 700; 
    padding: 16px 20px;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.card-body { padding: 24px; }
.list-body { max-height: 480px; overflow-y: auto; padding: 20px; }
.empty { text-align: center; color: #64748b; padding: 20px; font-style: italic; }

.usuario-item { 
    display: flex; 
    justify-content: space-between; 
    align-items: center; 
    padding: 16px; 
    border-radius: 12px; 
    background-color: #f3effa; 
    margin-bottom: 12px;
    border: 1px solid #e2d9f3;
    transition: transform 0.2s, box-shadow 0.2s;
}
.usuario-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}
.usuario-item .id-badge {
    background-color: #11431c;
    color: white;
    font-size: 0.75rem;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 12px;
    display: inline-block;
    margin-bottom: 4px;
}
.usuario-item .nombre {
    font-weight: 700;
    color: #11431c;
    font-size: 1.05rem;
}
.usuario-item .meta-info {
    font-size: 0.85rem;
    color: #475569;
    margin-top: 2px;
}

.usuario-item .acciones { display: flex; gap: 8px; }
.btn-accion { border: none; padding: 8px 12px; border-radius: 8px; cursor: pointer; font-size: 0.9rem; transition: background 0.2s; }
.btn-edit { background-color: #e2d9f3; color: #5b21b6; }
.btn-edit:hover { background-color: #d8b4fe; }
.btn-eliminar { background-color: #fee2e2; color: #991b1b; }
.btn-eliminar:hover { background-color: #fca5a5; }

/* Estado deshabilitado para acciones no permitidas */
.btn-disabled { opacity: 0.45; cursor: not-allowed; pointer-events: none; }

.form-group { margin-bottom: 20px; padding: 0 4px; }
.form-group label { 
    display: block; 
    font-weight: 700; 
    margin-bottom: 8px;
    color: #475569;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.form-group input { 
    width: 100%; 
    padding: 12px; 
    border: 1px solid #cbd5e1; 
    border-radius: 10px;
    font-family: inherit;
    font-size: 0.95rem;
    color: #1e293b;
    box-sizing: border-box;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.form-group input:focus {
    outline: none;
    border-color: #11431c;
    box-shadow: 0 0 0 3px rgba(17, 67, 28, 0.15);
}

.modal-actions { display: flex; flex-direction: column; gap: 10px; padding: 0 4px; margin-top: 24px; }
.btn-submit {
    background-color: #11431c; 
    color: white;
    border: none;
    padding: 12px;
    border-radius: 20px; 
    font-weight: 700;
    font-size: 0.95rem;
    cursor: pointer;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
    box-shadow: 0 4px 10px rgba(17, 67, 28, 0.2);
    transition: background-color 0.2s, transform 0.1s;
}
.btn-submit:hover { background-color: #0b2d13; }
.btn-submit:active { transform: scale(0.98); }

.btn-cancel {
    background-color: #ffffff; 
    color: #11431c;
    border: 1px solid #cbd5e1;
    padding: 12px;
    border-radius: 20px;
    font-weight: 700;
    font-size: 0.95rem;
    cursor: pointer;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
    transition: background-color 0.2s;
}
.btn-cancel:hover { background-color: #f8fafc; }

@media (max-width: 900px) { 
    .profiles-layout { flex-direction: column; } 
    .profiles-list-card, .profiles-form-card { width: 100%; }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    (function(){
    const token = '<?php echo e(csrf_token()); ?>';
    const principalEmail = '<?php echo e(session("email")); ?>' || '';
    const qs = (id)=> document.getElementById(id);
    let usuarios = [];

    function escapeHtml(s){ return String(s||'').replace(/[&<>"']/g, function(ch){ return {"&":"&amp;","<":"&lt;",">":"&gt;","\"":"&quot;","'":"&#39;"}[ch]; }); }

    // Carga los usuarios mediante fetch
    async function loadUsuarios(){
        try {
            const res = await fetch('/perfiles/list');
            usuarios = await res.json();
            console.log('perfiles/list response:', usuarios);
            render();
        } catch(e){ 
            console.error(e); 
            qs('lista-usuarios').innerHTML = '<div class="empty">Error cargando usuarios</div>'; 
        }
    }

    function render(){
        const cont = qs('lista-usuarios'); cont.innerHTML = '';
        if (!usuarios.length) { cont.innerHTML = '<div class="empty">No hay usuarios registrados</div>'; return; }
        usuarios.forEach(u=>{
            const div = document.createElement('div'); div.className='usuario-item';
            div.innerHTML = `
                <div class="meta">
                    <span class="id-badge">${escapeHtml(u.id_acceso)}</span>
                    <div class="nombre">${escapeHtml(u.nombre_completo)}</div>
                    <div class="meta-info">
                        <i class="fa-regular fa-envelope"></i> ${escapeHtml(u.correo)} | 
                        <i class="fa-solid fa-building"></i> ${escapeHtml(u.departamento)}
                    </div>
                </div>`;
            const acc = document.createElement('div'); acc.className='acciones';
            // Mostrar botones solo si el servidor indicó que se puede (bandera 'can_manage')
            const canManage = !!u.can_manage;
            if (canManage) {
                const btnE = document.createElement('button');
                btnE.className = 'btn-accion btn-edit';
                btnE.title = 'Editar';
                btnE.innerHTML = '<i class="fa-solid fa-pen-to-square"></i>';
                btnE.onclick = ()=> fillForm(u);

                const btnD = document.createElement('button');
                btnD.className = 'btn-accion btn-eliminar';
                btnD.title = 'Eliminar';
                btnD.innerHTML = '<i class="fa-solid fa-trash"></i>';
                btnD.onclick = ()=> eliminar(u.id);

                acc.appendChild(btnE); acc.appendChild(btnD);
            }
            div.appendChild(acc);
            cont.appendChild(div);
        });
    }

    function fillForm(u){ 
        qs('usuario_id').value = u.id; 
        qs('nombre_completo').value = u.nombre_completo; 
        qs('correo').value = u.correo; 
        qs('departamento').value = u.departamento;
        qs('contrasena').value = ''; 
        qs('contrasena').required = false; 
        window.scrollTo({top:0,behavior:'smooth'}); 
    }
    
    function resetForm(){ 
        qs('usuario_id').value=''; 
        qs('nombre_completo').value=''; 
        qs('correo').value=''; 
        qs('departamento').value='';
        qs('contrasena').value=''; 
        qs('contrasena').required = true; 
    }

    // ELIMINAR: Integrado con SweetAlert2 elegante y adaptado a tus estilos institucionales
    function eliminar(id){ 
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción eliminará la cuenta de usuario. Perderá el acceso de inmediato.",
            icon: 'warning',
            borderRadius: '25px',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#e2e8f0',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: '<span style="color:#334155">Cancelar</span>'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('/perfiles/' + id, { 
                    method: 'DELETE', 
                    headers: { 
                        'X-CSRF-TOKEN': token, 
                        'Accept': 'application/json' 
                    } 
                })
                .then(async res => {
                    const data = await res.json();
                    if (!res.ok) throw new Error(data.message || 'Error del servidor');
                    return data;
                })
                .then(data => {
                    if(data.success) {
                        Swal.fire({
                            title: '¡Eliminado!',
                            text: 'El usuario ha sido removido del sistema con éxito.',
                            icon: 'success',
                            confirmButtonColor: '#11431c',
                            borderRadius: '25px'
                        });
                        loadUsuarios();
                    } else {
                        Swal.fire('Error', data.message || 'No se pudo completar la acción', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'No se pudo eliminar: ' + error.message, 'error');
                });
            }
        });
    }

    // GUARDAR / ACTUALIZAR: Integrado con SweetAlert2 para mostrar respuestas claras
    async function submit(e){ 
        e.preventDefault(); 
        const id = qs('usuario_id').value; 
        const payload = { 
            nombre_completo: qs('nombre_completo').value.trim(), 
            correo: qs('correo').value.trim(),
            departamento: qs('departamento').value.trim(),
            contrasena: qs('contrasena').value
        }; 
        
        const method = id ? 'PUT' : 'POST'; 
        const url = id ? '/perfiles/' + id : '/perfiles'; 
        
        try { 
            const res = await fetch(url, { 
                method, 
                headers: { 
                    'X-CSRF-TOKEN': token, 
                    'Content-Type': 'application/json', 
                    'Accept': 'application/json' 
                }, 
                body: JSON.stringify(payload) 
            }); 
            const data = await res.json(); 
            
            if (res.ok && data.success){ 
                resetForm(); 
                loadUsuarios(); 
                Swal.fire({
                    title: '¡Éxito!',
                    text: data.message || 'Operación realizada correctamente.',
                    icon: 'success',
                    confirmButtonColor: '#11431c',
                    borderRadius: '25px'
                });
            } else if (data.errors){ 
                Swal.fire({
                    title: 'Validación',
                    text: Object.values(data.errors).flat().join('\n'),
                    icon: 'error',
                    confirmButtonColor: '#11431c',
                    borderRadius: '25px'
                });
            } else {
                Swal.fire('Error', data.message || 'Ocurrió un error inesperado', 'error');
            }
        } catch(e){ 
            console.error(e); 
            Swal.fire('Error', 'Error de red al conectar con el servidor', 'error');
        } 
    }

    document.addEventListener('DOMContentLoaded', function(){ 
        loadUsuarios(); 
        qs('formUsuario').addEventListener('submit', submit); 
        qs('cancelUsuario').addEventListener('click', resetForm); 
    });
})();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/usuarios/index.blade.php ENDPATH**/ ?>