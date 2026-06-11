@extends('layouts.app')

@section('title', 'Empleados')
@section('header', 'Directorio de Personal')

@section('content')
<div class="employees-container">
    <div class="employees-header">
        <h2>Control de Empleados</h2>
        <p>Resumen general del personal activo.</p>
    </div>

    <div class="employees-actions-bar">
        <div class="button-add-employee">
            <button onclick="abrirModal('modalAgregar')" class="btn-add">
                <i class="fa-solid fa-user-plus"></i> 
                <span class="btn-text">Agregar Empleado</span>
            </button>
        </div>

        <form action="{{ route('empleados.index') }}" method="GET" class="search-form">
            <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar empleado..." class="search-input" aria-label="Buscar empleado">
            <button type="submit" class="btn-search" title="Buscar"><i class="fas fa-search"></i></button>
            @if(request('buscar'))
                <a href="{{ route('empleados.index') }}" class="btn-clear-search" title="Limpiar búsqueda"><i class="fas fa-times"></i></a>
            @endif
        </form>
    </div>
    
    <div class="table-container">
        @php $canManage = session('email') === 'admin@sistema.com'; @endphp
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
                @forelse($empleados as $emp)
                <tr id="fila-empleado-{{ $emp->id }}" style="transition: all 0.4s ease;">
                    <td><strong>{{ ($empleados->currentPage() - 1) * $empleados->perPage() + $loop->iteration }}</strong></td>
                    <td class="td-nombre">{{ $emp->nombre }} {{ $emp->apellido_paterno }} {{ $emp->apellido_materno }}</td>
                    <td class="td-puesto">{{ $emp->puesto->nombre ?? 'Sin Puesto' }}</td>
                    <td style="text-align: left;">
                        <div class="contenedor-acciones">
                            <a href="{{ route('empleados.vacaciones', $emp->id) }}" class="btn-accion btn-vacaciones" title="Vacaciones">
                                <i class="fa-solid fa-calendar"></i>
                            </a>

                            {{-- PDF solo para administradores --}}
                            @if($canManage)
                                <a href="/empleados/{{ $emp->id }}/vacaciones/pdf" target="_blank" class="btn-accion btn-pdf" title="Descargar PDF">
                                    <i class="fa-solid fa-file-pdf"></i>
                                </a>
                            @endif

                            {{-- Editar/Eliminar: visibles pero bloqueados para no administradores --}}
                            @if($canManage)
                                <button onclick="abrirModalEditar({{ json_encode($emp) }})" class="btn-accion btn-edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <button type="button" class="btn-accion btn-eliminar" onclick="ejecutarSoftDelete(this)" data-id="{{ $emp->id }}">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            @else
                                <button class="btn-accion btn-edit btn-disabled" aria-disabled="true" title="No autorizado">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <button class="btn-accion btn-eliminar btn-disabled" aria-disabled="true" title="No autorizado">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            @endif

                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" style="text-align: center; padding: 2rem; color: #64748b;">No se encontraron empleados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(method_exists($empleados, 'hasPages') && $empleados->hasPages())
        <div class="custom-pagination-container">
            {{ $empleados->appends(request()->query())->links('pagination::bootstrap-4') }}
        </div>
    @endif
</div>

{{-- MODAL AGREGAR --}}
<div id="modalAgregar" class="modal">
    <div class="modal-content">
        <h3 class="modal-header add"><i class="fa-solid fa-user-plus"></i> Nuevo Empleado</h3>
        <form action="{{ route('empleados.store') }}" method="POST">
            @csrf 
            <div class="form-group"><label>Nombre(s)</label><input type="text" name="nombre" required></div>
            <div class="form-group"><label>Apellido Paterno</label><input type="text" name="apellido_paterno" required></div>
            <div class="form-group"><label>Apellido Materno</label><input type="text" name="apellido_materno"></div>
            <div class="form-group">
                <label>Puesto</label>
                <div style="display: flex; gap: 8px;">
                    <select name="puesto_id" required style="flex-grow:1;">
                        <option value="" disabled selected>Selecciona un puesto...</option>
                        @foreach($puestos as $puesto)<option value="{{ $puesto->id }}">{{ $puesto->nombre }}</option>@endforeach
                    </select>
                    <button type="button" onclick="abrirModal('modalPuesto', this.previousElementSibling)" class="btn-add-puesto">+</button>
                </div>
            </div>
            <div class="form-group"><label>Fecha de Ingreso</label><input type="date" name="fecha_ingreso" required></div>
            <div class="modal-actions">
                <button type="button" onclick="cerrarModal('modalAgregar')" class="btn-cancel">Cancelar</button>
                <button type="submit" class="btn-submit">Guardar</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDITAR --}}
<div id="modalEditar" class="modal">
    <div class="modal-content">
        <h3 class="modal-header edit"><i class="fa-solid fa-user-pen"></i> Editar Empleado</h3>
        <form id="formEditar" method="POST">
            @csrf @method('PUT')
            <div class="form-group"><label>Nombre(s)</label><input type="text" name="nombre" id="edit_nombre" required></div>
            <div class="form-group"><label>Apellido Paterno</label><input type="text" name="apellido_paterno" id="edit_paterno" required></div>
            <div class="form-group"><label>Apellido Materno</label><input type="text" name="apellido_materno" id="edit_materno"></div>
            <div class="form-group">
                <label>Puesto</label>
                <div style="display: flex; gap: 8px;">
                    <select name="puesto_id" id="edit_puesto_id" required style="flex-grow:1;">
                        @foreach($puestos as $puesto)<option value="{{ $puesto->id }}">{{ $puesto->nombre }}</option>@endforeach
                    </select>
                    <button type="button" onclick="abrirModal('modalPuesto', this.previousElementSibling)" class="btn-add-puesto">+</button>
                </div>
            </div>
            <div class="form-group"><label>Fecha de Ingreso</label><input type="date" name="fecha_ingreso" id="edit_fecha" required></div>
            <div class="modal-actions">
                <button type="button" onclick="cerrarModal('modalEditar')" class="btn-cancel">Cancelar</button>
                <button type="submit" class="btn-submit edit">Actualizar</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL NUEVO PUESTO --}}
<div id="modalPuesto" class="modal">
    <div class="modal-content" style="max-width: 400px;">
        <h3 class="modal-header add"><i class="fa-solid fa-briefcase"></i> Nuevo Puesto</h3>
        <form action="{{ route('puestos.store') }}" method="POST">
            @csrf
            <div class="form-group"><label>Nombre del Puesto</label><input type="text" name="nombre" required></div>
            <div class="modal-actions">
                <button type="button" onclick="cerrarModal('modalPuesto')" class="btn-cancel">Cancelar</button>
                <button type="submit" class="btn-submit">Guardar</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let lastPuestoSelect = null;
    function abrirModal(id, relatedSelect = null) { if (relatedSelect) lastPuestoSelect = relatedSelect; document.getElementById(id).classList.add('show'); }
    function cerrarModal(id) { document.getElementById(id).classList.remove('show'); lastPuestoSelect = null; }

    // Evita que los modales se cierren al hacer clic dentro
    document.querySelectorAll('.modal-content').forEach(content => {
        content.addEventListener('click', e => e.stopPropagation());
    });

    function abrirModalEditar(empleado) {
        const form = document.getElementById('formEditar');
        form.action = `/empleados/${empleado.id}`;
        document.getElementById('edit_nombre').value = empleado.nombre;
        document.getElementById('edit_paterno').value = empleado.apellido_paterno;
        document.getElementById('edit_materno').value = empleado.apellido_materno || '';
        document.getElementById('edit_puesto_id').value = empleado.puesto_id;
        document.getElementById('edit_fecha').value = empleado.fecha_ingreso;
        abrirModal('modalEditar');
    }
    
    function cerrarModalEditar() { cerrarModal('modalEditar'); }

    // Manejo AJAX para agregar un nuevo puesto desde el modal sin recargar la página
    (function(){
        const form = document.querySelector('#modalPuesto form');
        if (!form) return;

        form.addEventListener('submit', async function(e){
            e.preventDefault();
            const url = form.action;
            const formData = new FormData(form);
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            try {
                const res = await fetch(url, { method: 'POST', headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' }, body: formData });
                if (!res.ok) {
                    const text = await res.text();
                    console.error('Error response:', text);
                    alert('Error al guardar el puesto.');
                    return;
                }

                const data = await res.json();
                const option = document.createElement('option');
                option.value = data.id;
                option.textContent = data.nombre;

                // Añadir la opción a todos los selects relevantes
                document.querySelectorAll('select[name="puesto_id"], select#edit_puesto_id').forEach(s => s.appendChild(option.cloneNode(true)));

                // Seleccionar la nueva opción en el select que abrió el modal (si existe)
                if (lastPuestoSelect) {
                    lastPuestoSelect.value = data.id;
                } else {
                    const sel = document.querySelector('#modalAgregar select[name="puesto_id"]');
                    if (sel) sel.value = data.id;
                }

                form.reset();
                cerrarModal('modalPuesto');
            } catch (err) {
                console.error(err);
                alert('Ocurrió un error al guardar el puesto.');
            }
        });
    })();

    // Tu lógica original de soft delete y window.onclick permanece activa
    async function ejecutarSoftDelete(btn) {
        const id = btn.getAttribute('data-id');
        if (!id) return;

        const result = await Swal.fire({
            title: '¿Estás seguro?',
            text: 'Esta acción eliminará al empleado (soft delete).',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        });

        if (!result.isConfirmed) return;

        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        try {
            const res = await fetch(`/empleados/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' } });
            if (!res.ok) {
                const body = await res.json().catch(() => ({}));
                Swal.fire('Error', body.message || 'No se pudo eliminar el empleado.', 'error');
                return;
            }

            // Animar y remover la fila
            const row = document.getElementById(`fila-empleado-${id}`);
            if (row) {
                row.classList.add('row-fade-out');
                setTimeout(() => row.remove(), 500);
            }

            Swal.fire({ icon: 'success', title: 'Eliminado', text: 'Empleado eliminado correctamente.', timer: 1500, showConfirmButton: false });
        } catch (err) {
            console.error(err);
            Swal.fire('Error', 'Ocurrió un error al eliminar.', 'error');
        }
    }

    // Mostrar alerta de éxito si la sesión tiene mensaje
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: @json(session('success')),
            timer: 2000,
            showConfirmButton: false
        });
    @endif
</script>
@endpush

@push('styles')
<style>
    .btn-add-puesto { background: #124416; color: white; border: none; padding: 0 15px; border-radius: 8px; cursor: pointer; font-weight: 700; }
    .btn-add-puesto:hover { background: #0e3310; }
    /* Tus estilos originales se conservan */
</style>
@endpush

{{-- 3. BLOQUE DE ESTILOS CSS --}}
@push('styles')
<style>
    .employees-header {
            background: white; 
            padding: 24px 30px; 
            border-radius: 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
            text-align: center;
            margin-bottom: 25px;
            position: relative;
            overflow: hidden;
        }
        .employees-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background-color: #a87e3b; 
        }
        .employees-header h2 {
            margin: 0 0 8px 0;
            color: #2b0b4d; 
            font-size: 1.8rem;
            font-weight: 700;
        }
    .employees-header p {
            margin: 0;
            color: #5e7087;
            font-size: 0.95rem;
        }

    /* Acciones: Distribuidas uniformemente a los extremos opuestos */
    .employees-actions-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        gap: 1rem;
        flex-wrap: wrap;
    }

    /* Estilo del Buscador */
    .search-form {
        display: flex;
        align-items: center;
        background-color: #123819; 
        border: 1px solid rgba(255, 255, 255, 0.25); 
        border-radius: 30px; 
        padding: 3px 6px 3px 18px;
        max-width: 400px;
        width: 100%;
        box-sizing: border-box;
        position: relative;
    }

    .search-input {
        border: none;
        background: transparent;
        padding: 8px 0;
        width: 100%;
        outline: none;
        font-size: 0.95rem;
        color: #ffffff; 
    }

    .search-input::placeholder {
        color: rgba(255, 255, 255, 0.7); 
    }

    .btn-search {
        border: none;
        background-color: #a8793b; 
        color: white;
        cursor: pointer;
        width: 36px;
        height: 36px;
        border-radius: 50%; 
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        transition: background-color 0.2s, transform 0.2s;
        flex-shrink: 0;
        margin-left: 6px;
    }

    .btn-search:hover {
        background-color: #916630;
        transform: scale(1.05);
    }

    .btn-clear-search {
        border: none;
        background: transparent;
        color: rgba(255, 255, 255, 0.6);
        cursor: pointer;
        padding: 6px;
        font-size: 0.95rem;
        transition: color 0.2s;
        display: flex;
        align-items: center;
        margin-left: auto;
    }

    .btn-clear-search:hover { color: #f1f5f9; }

    /* Botón Agregar Empleado a la izquierda */
    .button-add-employee {
        display: flex;
        justify-content: flex-start;
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

    /* Estructura de Tabla */
    .table-container {
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        overflow-x: auto;
        border-radius: 20px;
    }

    .tabla-empleados {
        width: 100%;
        border-collapse: collapse;
        font-family: inherit;
        margin: 0;
    }

    .tabla-empleados th {
        background-color: #124416;
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
        transition: all 0.4s ease;
    }

    .tabla-empleados tr:hover td {
        background-color: #f8fafc;
    }

    .td-nombre { color: #334155; }
    .td-puesto { color: #64748b; }

    /* Botoneras */
    .contenedor-acciones {
        display: flex;
        gap: 8px;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
    }

    .btn-accion {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        font-size: 13px;
        font-weight: 600;
        border-radius: 20px;
        border: none;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    
    .btn-edit { background-color: #AA7F31; color: white; gap: 20px; }
    .btn-edit:hover { background-color: #8c6827; transform: translateY(-1px); }
    .btn-edit .btn-text { display: none;}

    .btn-vacaciones { background-color: #124416; color: white; }
    .btn-vacaciones:hover { background-color: #0e3310; transform: translateY(-1px); }
    .btn-pdf { background-color: #b91c1c; color: white; }
    .btn-pdf:hover { background-color: #951616; transform: translateY(-1px); }
    .btn-disabled { background: #e2e8e5 !important; color: #9aa3a0 !important; cursor: not-allowed; box-shadow: none; }
    .btn-disabled i { opacity: 0.6; }
    
    .btn-eliminar .btn-text { display: none; }
    .btn-eliminar { background-color: #dc3545; color: white; align-items: center; }
    .btn-eliminar:hover { background-color: #bd2130; transform: translateY(-1px);   }

    .row-fade-out {
        opacity: 0;
        transform: translateX(-20px);
        transition: all 0.5s ease;
    }

    /* Paginación */
    .custom-pagination-container {
        margin-top: 2rem;
        display: flex;
        justify-content: right;
    }

    .custom-pagination-container .pagination {
        display: flex;
        gap: 8px; 
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .custom-pagination-container .page-item .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background-color: #f8fafc; 
        color: #123819; 
        border: 1px solid #e2e8f0;
        border-radius: 8px !important; 
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .custom-pagination-container .page-link:hover {
        background-color: #e2e8f0;
        color: #123819;
    }

    .custom-pagination-container .page-item.active .page-link {
        background-color: #123819 !important;
        color: #ffffff !important;
        border-color: #123819 !important;
        cursor: default;
    }

    .custom-pagination-container .page-item.disabled .page-link {
        background-color: #f8fafc;
        color: #94a3b8;
        border-color: #e2e8f0;
        cursor: not-allowed;
        opacity: 0.6;
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

    .modal.show { display: flex; }

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

    .modal-header.edit { color: #AA7F31; border-bottom-color: #124416; }
    .modal-header.add { color: #124416; }

    .form-group { margin-bottom: 1rem; }
    .form-group label {
        display: block;
        color: #334155;
        font-weight: 600;
        margin-bottom: 0.3rem;
        font-size: 0.9rem;
    }

    .form-group input, .form-group select {
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

    .form-group input:focus, .form-group select:focus {
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
    .btn-cancel:hover { background: #cbd5e1; }

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
    .btn-submit:hover { background: #0e3310; transform: translateY(-1px); }

    .btn-submit.edit { background: #AA7F31; }
    .btn-submit.edit:hover { background: #8c6827; }

    @media (max-width: 768px) {
        .employees-actions-bar { flex-direction: column-reverse; align-items: stretch; }
        .search-form { max-width: 100%; }
        .button-add-employee { justify-content: center; }
        .custom-pagination-container { justify-content: center; }
        .contenedor-acciones { gap: 0.3rem; }
        .btn-accion { padding: 0.4rem 0.8rem; font-size: 0.75rem; }
    }
</style>
@endpush