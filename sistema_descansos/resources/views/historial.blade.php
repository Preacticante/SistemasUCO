@extends('layouts.app')

@section('title', 'Historial')
@section('header', 'Directorio de vacaciones')

@section('content')
    <div class="panel-principal-header">
        <h2>Historial de Vacaciones</h2>
        <p>Resumen general del estado de vacaciones y alertas de personal activo.</p>
    </div>

    <div class="table-card-container">
        <div class="table-card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
            <span><i class="fas fa-list-ul"></i> Registro de Solicitudes</span>
            
            <form action="{{ route('historial') }}" method="GET" class="search-form">
                <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar empleado..." class="search-input">
                <button type="submit" class="btn-search" title="Buscar"><i class="fas fa-search"></i></button>
                @if(request('buscar'))
                    <a href="{{ route('historial') }}" class="btn-clear-search" title="Limpiar búsqueda"><i class="fas fa-times"></i></a>
                @endif
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
    @php $canManage = session('email') === 'admin@sistema.com'; @endphp
    <tbody>
        @forelse($periodosVacacionales as $periodo)
            @php
                $empleado = $periodo->empleado;
                $fechaFin = \Carbon\Carbon::parse($periodo->fecha_fin);
                $yaTomado = $fechaFin->isPast();
                $estado = $yaTomado ? 'Tomado' : 'Programado';
            @endphp
            <tr>
                <td class="text-employee-name">
                    @if($empleado)
                        {{ $empleado->nombre }} {{ $empleado->apellido_paterno }} {{ $empleado->apellido_materno }}
                    @else
                        <span class="text-muted" style="font-style: italic;">Empleado eliminado</span>
                    @endif
                </td>
                <td style="color: #64748b; font-weight: 500;">Vacaciones</td>
                <td style="color: #334155;">{{ \Carbon\Carbon::parse($periodo->fecha_inicio)->format('d/m/Y') }}</td>
                <td style="color: #334155;">{{ $fechaFin->format('d/m/Y') }}</td>
                <td class="{{ $yaTomado ? 'text-muted-days' : 'text-danger-bold' }}">
                    {{ $periodo->dias }} día{{ $periodo->dias === 1 ? '' : 's' }}
                </td>
                <td>
                    <span class="badge {{ $yaTomado ? 'badge-success' : 'badge-info' }}">{{ $estado }}</span>
                </td>
                <td style="text-align: center; display: flex; gap: 8px; justify-content: center; align-items: center;">
                    
                    {{-- VERIFICACIÓN: Solo mostramos el enlace si el empleado existe --}}
                    @if($empleado)
                        <a href="{{ route('empleados.vacaciones', $empleado->id) }}" class="btn-action-vacaciones" title="Vacaciones">
                            <i class="fas fa-calendar"></i>
                        </a>

                        @if($yaTomado)
                            <button type="button" class="btn-action-edit btn-disabled" title="No se puede editar un período ya tomado" disabled>
                                <i class="fas fa-lock"></i> Completado
                            </button>
                        @else
                            @if($canManage)
                                <button type="button" class="btn-action-edit" onclick="openEditModal({{ $periodo->id }}, {{ $empleado->id }})">
                                    <i class="fas fa-pencil"></i>
                                </button>
                                <button type="button" class="btn-action-delete" onclick="deletePeriodo({{ $periodo->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <a href="/empleados/{{ $empleado->id }}/vacaciones/pdf?periodo_id={{ $periodo->id }}" target="_blank" class="btn-action-pdf" title="Descargar comprobante">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </a>
                            @else
                                <button type="button" class="btn-action-edit btn-disabled" disabled><i class="fas fa-pencil"></i></button>
                                <button type="button" class="btn-action-delete btn-disabled" disabled><i class="fas fa-trash"></i></button>
                            @endif
                        @endif
                    @else
                        <button class="btn-disabled" title="Datos de empleado no disponibles" disabled>
                            <i class="fas fa-exclamation-triangle"></i>
                        </button>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" style="text-align:center; padding: 40px 0; color: #5e7087;">
                    <i class="fas fa-folder-open" style="font-size: 2.5rem; color: #cbd5e1; margin-bottom: 15px; display: block;"></i>
                    No se encontraron registros de vacaciones en el sistema.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
        </div>

        @if($periodosVacacionales->hasPages())
            <div class="pagination-container">
                {{ $periodosVacacionales->links('pagination::bootstrap-4') }}
            </div>
        @endif
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

        .btn-action-vacaciones { border: none; cursor: pointer; padding: 6px 12px; border-radius: 6px; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; color: white; background-color: #124416; text-decoration: none; }
        .btn-action-vacaciones:hover { background-color: #0d2e10; transform: translateY(-2px); }

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
            border-radius: 20px;
            width: 95%;
            max-width: 550px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            max-height: 90vh;
            overflow-y: auto;
        }
        .modal-edit-content h3 { margin-top: 0; color: #124416; font-size: 1.4rem; font-weight: 700; display: flex; align-items: center; gap: 10px; }
        .modal-edit-content .form-group { margin-bottom: 15px; }
        .modal-edit-content label { display: block; margin-bottom: 5px; color: #334155; font-weight: 600; font-size: 0.9rem; }
        
        .modal-edit-content input[type="text"], 
        .modal-edit-content textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 0.95rem;
            box-sizing: border-box;
            font-family: inherit;
        }
        .modal-edit-content input:focus, 
        .modal-edit-content textarea:focus { outline: none; border-color: #124416; box-shadow: 0 0 0 3px rgba(18, 68, 22, 0.1); }
        
        /* ESTILOS DEL CALENDARIO INCORPORADO */
        .calendar-container {
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 15px;
            background: #fff;
            user-select: none;
        }
        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            font-weight: bold;
            color: #124416;
        }
        .calendar-header button {
            background: transparent;
            border: none;
            cursor: pointer;
            font-size: 1.1rem;
            color: #64748b;
        }
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
            text-align: center;
        }
        .day-name {
            font-size: 0.8rem;
            color: #64748b;
            font-weight: 600;
            padding-bottom: 5px;
        }
        .calendar-day {
            padding: 10px 0;
            font-size: 0.9rem;
            border-radius: 50%;
            cursor: pointer;
            color: #334155;
            display: flex;
            align-items: center;
            justify-content: center;
            aspect-ratio: 1;
            transition: all 0.15s ease;
        }
        .calendar-day:hover:not(.empty):not(.disabled) {
            background-color: #f1f5f9;
        }
        .calendar-day.empty { cursor: default; color: #cbd5e1; }
        .calendar-day.disabled { color: #cbd5e1; cursor: not-allowed; }
        
        /* Colores de estados según tus imágenes */
        .calendar-day.selected-range {
            background-color: #2b0b4d !important; /* Color Morado de Rangos Seleccionados */
            color: white !important;
            font-weight: bold;
        }
        .calendar-day.festivo-range {
            background-color: #a87e3b !important; /* Color Dorado/Café de Festivos */
            color: white !important;
            font-weight: bold;
        }

        .modal-edit-buttons { display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px; }
        .modal-edit-buttons button {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-save { background-color: #124416; color: white; }
        .btn-save:hover { background-color: #0d2e10; }
        .btn-cancel { background-color: #e2e8f0; color: #334155; }
        .btn-cancel:hover { background-color: #cbd5e1; }
        
        .selected-days-summary {
            font-size: 0.9rem;
            color: #475569;
            font-weight: 600;
            margin-top: 8px;
        }
    </style>

    <div id="editModal" class="modal-edit">
        <div class="modal-edit-content">
            <h3><i class="fas fa-calendar-plus"></i> Registrar / Editar vacaciones</h3>
            <p style="color: #64748b; font-size: 0.9rem; margin-top:-5px; margin-bottom:15px;">Selecciona los días en el calendario</p>
            
            <form id="editForm">
                @csrf
                <div class="form-group">
                    <label for="editEmpleado">Empleado:</label>
                    <input type="text" id="editEmpleado" readonly style="background-color: #f8fafc; color: #64748b; font-weight: 500;">
                </div>

                <div class="form-group">
                    <label>Calendario de fechas:</label>
                    <div class="calendar-container">
                        <div class="calendar-header">
                            <button type="button" onclick="changeMonth(-1)"><i class="fas fa-chevron-left"></i></button>
                            <span id="calendarMonthYear">Junio 2026</span>
                            <button type="button" onclick="changeMonth(1)"><i class="fas fa-chevron-right"></i></button>
                        </div>
                        <div class="calendar-grid" id="calendarGrid">
                            </div>
                    </div>
                    <div class="selected-days-summary" id="daysSummary">
                        Días seleccionados a descontar: 0 días
                    </div>
                </div>

                <div class="form-group">
                    <label for="editObservaciones">Observaciones:</label>
                    <textarea id="editObservaciones" rows="3" placeholder="Escribe aquí algún comentario o motivo sobre el cambio de período..."></textarea>
                </div>

                <div class="modal-edit-buttons">
                    <button type="button" class="btn-cancel" onclick="closeEditModal()">Cancelar</button>
                    <button type="button" class="btn-save" onclick="guardarEdicion()">Guardar</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>

let csrfTokenHist = "{{ csrf_token() }}";
let periodoEnEdicion = null;
let empleadoEnEdicion = null;

// Variables de estado del calendario interactivo
let currentYear = 2026;
let currentMonth = 5; // Junio (0-indexed)
let startDateSelected = null;
let endDateSelected = null;
let diasFestivosGlobales = []; 

const monthNames = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

function openEditModal(id, empleadoId) {
    periodoEnEdicion = id;
    empleadoEnEdicion = empleadoId;

    Swal.fire({
        title: 'Cargando...',
        text: 'Obteniendo información del período',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch(`/periodos/${id}`)
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
                    confirmButtonColor: '#124416'
                });

                periodoEnEdicion = null;
                empleadoEnEdicion = null;
                return; // <--- Este return es VÁLIDO porque está dentro de la función .then()
            }

            document.getElementById('editEmpleado').value = data.empleado_nombre || 'N/A';
            document.getElementById('editObservaciones').value = data.observaciones || '';

            if(data.fecha_inicio && data.fecha_fin) {
                const pInicio = data.fecha_inicio.split('-');
                const pFin = data.fecha_fin.split('-');
                
                startDateSelected = new Date(pInicio[0], pInicio[1] - 1, pInicio[2]);
                endDateSelected = new Date(pFin[0], pFin[1] - 1, pFin[2]);
                
                currentYear = parseInt(pInicio[0]);
                currentMonth = parseInt(pInicio[1]) - 1;
            } else {
                startDateSelected = null;
                endDateSelected = null;
            }

            renderCalendar();
            document.getElementById('editModal').classList.add('show');
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo cargar el período.',
                confirmButtonColor: '#dc2626'
            });
        });
}

function renderCalendar() {
    const grid = document.getElementById('calendarGrid');
    if (!grid) return; // <--- VÁLIDO: Retorno seguro si no existe el elemento en el DOM
    
    document.getElementById('calendarMonthYear').innerText = `${monthNames[currentMonth]} ${currentYear}`;
    grid.innerHTML = '';

    const daysLetters = ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'];
    daysLetters.forEach(d => {
        const dDiv = document.createElement('div');
        dDiv.className = 'day-name';
        dDiv.innerText = d;
        grid.appendChild(dDiv);
    });

    const firstDayIndex = new Date(currentYear, currentMonth, 1).getDay(); 
    const totalDays = new Date(currentYear, currentMonth + 1, 0).getDate();
    let startOffset = firstDayIndex === 0 ? 6 : firstDayIndex - 1;

    for(let i = 0; i < startOffset; i++) {
        const emptyDiv = document.createElement('div');
        emptyDiv.className = 'calendar-day empty';
        grid.appendChild(emptyDiv);
    }

    for(let day = 1; day <= totalDays; day++) {
        const dayDiv = document.createElement('div');
        dayDiv.className = 'calendar-day';
        dayDiv.innerText = day;

        const thisDate = new Date(currentYear, currentMonth, day);

        if (startDateSelected && endDateSelected && thisDate >= startDateSelected && thisDate <= endDateSelected) {
            dayDiv.classList.add('selected-range'); 
        } else if (startDateSelected && thisDate.getTime() === startDateSelected.getTime()) {
            dayDiv.classList.add('selected-range'); 
        }

        dayDiv.onclick = () => {
            selectDate(thisDate);
        };

        grid.appendChild(dayDiv);
    }

    updateSummary();
}

function changeMonth(direction) {
    currentMonth += direction;
    if (currentMonth < 0) {
        currentMonth = 11;
        currentYear--;
    } else if (currentMonth > 11) {
        currentMonth = 0;
        currentYear++;
    }
    renderCalendar();
}

function selectDate(date) {
    if (!startDateSelected || (startDateSelected && endDateSelected)) {
        startDateSelected = date;
        endDateSelected = null;
    } else if (startDateSelected && !endDateSelected) {
        if (date < startDateSelected) {
            startDateSelected = date;
        } else {
            endDateSelected = date;
        }
    }
    renderCalendar();
}

function updateSummary() {
    const summary = document.getElementById('daysSummary');
    if (!summary) return; // <--- VÁLIDO: Evita que truene si no existe el contenedor de texto

    if (startDateSelected && endDateSelected) {
        const diffTime = Math.abs(endDateSelected - startDateSelected);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
        summary.innerText = `Días seleccionados a descontar: ${diffDays} día${diffDays === 1 ? '' : 's'}`;
    } else if (startDateSelected) {
        summary.innerText = `Días seleccionados a descontar: 1 día`;
    } else {
        summary.innerText = `Días seleccionados a descontar: 0 días`;
    }
}

function closeEditModal() {
    const modal = document.getElementById('editModal');
    if (modal) modal.classList.remove('show');
    
    periodoEnEdicion = null;
    empleadoEnEdicion = null;
    startDateSelected = null;
    endDateSelected = null;
}

function guardarEdicion() {
    if (!periodoEnEdicion) return; // <--- VÁLIDO

    // Intentar buscar los controles de fecha por ID o por atributo Name
    const inputInicio = document.getElementById('editFechaInicio') || document.querySelector('input[name="fecha_inicio"]');
    const inputFin = document.getElementById('editFechaFin') || document.querySelector('input[name="fecha_fin"]');
    const inputObservaciones = document.getElementById('editObservaciones');

    // Si los inputs de texto no existen en este formulario, usamos los datos guardados del calendario interactivo
    let fechaInicioStr = "";
    let fechaFinStr = "";

    if (inputInicio && inputFin && inputInicio.value && inputFin.value) {
        const convertirAFormatovAlido = (fechaStr) => {
            const partes = fechaStr.includes('/') ? fechaStr.split('/') : fechaStr.split('-');
            if (partes.length === 3) {
                if (partes[0].length <= 2 && partes[2].length === 4) {
                    return `${partes[2]}-${partes[1].padStart(2, '0')}-${partes[0].padStart(2, '0')}`;
                }
                if (partes[0].length === 4) {
                    return `${partes[0]}-${partes[1].padStart(2, '0')}-${partes[2].padStart(2, '0')}`;
                }
            }
            return fechaStr;
        };
        fechaInicioStr = convertirAFormatovAlido(inputInicio.value);
        fechaFinStr = convertirAFormatovAlido(inputFin.value);
    } else if (startDateSelected) {
        // Si usas el calendario dinámico
        const finalEnd = endDateSelected ? endDateSelected : startDateSelected;
        const formatDate = (d) => {
            let month = '' + (d.getMonth() + 1);
            let day = '' + d.getDate();
            let year = d.getFullYear();
            if (month.length < 2) month = '0' + month;
            if (day.length < 2) day = '0' + day;
            return [year, month, day].join('-');
        };
        fechaInicioStr = formatDate(startDateSelected);
        fechaFinStr = formatDate(finalEnd);
    } else {
        Swal.fire({
            icon: 'warning',
            title: 'Campos incompletos',
            text: 'Por favor selecciona un rango de días o introduce las fechas.',
            confirmButtonColor: '#124416'
        });
        return; // <--- VÁLIDO
    }

    const observaciones = inputObservaciones ? inputObservaciones.value : '';

    Swal.fire({
        title: 'Guardando cambios...',
        text: 'Por favor espera',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch(`/periodos/${periodoEnEdicion}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfTokenHist
        },
        body: JSON.stringify({
            fecha_inicio: fechaInicioStr,
            fecha_fin: fechaFinStr,
            observaciones: observaciones
        })
    })
    .then(async response => {
        const data = await response.json();
        if (!response.ok) {
            if (response.status === 422 && data.errors) {
                let errorMessages = Object.values(data.errors).flat().join('\n');
                throw new Error(errorMessages);
            }
            throw new Error(data.error || data.message || 'Error del servidor');
        }
        return data;
    })
    .then(data => {
        Swal.fire({
            icon: 'success',
            title: '¡Actualizado!',
            text: 'El período vacacional fue recalculado y modificado correctamente.',
            confirmButtonColor: '#124416'
        }).then(() => {
            closeEditModal();
            location.reload();
        });
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Validación de datos fallida',
            text: error.message,
            confirmButtonColor: '#dc2626'
        });
    });
}

function deletePeriodo(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción eliminará la solicitud y restaurará los días al balance del empleado.',
        icon: 'warning',
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
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(`/periodos/${id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfTokenHist
                }
            })
            .then(async response => {
                const data = await response.json();
                if (!response.ok) throw new Error(data.error || 'Error del servidor');
                return data;
            })
            .then(data => {
                Swal.fire({
                    title: '¡Eliminado!',
                    text: 'Los días se han restaurado correctamente.',
                    icon: 'success',
                    confirmButtonColor: '#124416'
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
                    confirmButtonColor: '#dc2626'
                });
            });
        }
    });
}

// Event listener asignado de forma segura al cargar el archivo
document.addEventListener('DOMContentLoaded', () => {
    const modalElement = document.getElementById('editModal');
    if (modalElement) {
        modalElement.addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });
    }
});

</script>
@endpush