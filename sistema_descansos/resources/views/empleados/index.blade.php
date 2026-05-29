@extends('layouts.app')

@section('title', 'Empleados')
@section('header', 'Directorio de Personal')

@section('content')
<div style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); position: relative;">
    
    <div style="
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        padding: 25px 40px;
        border-radius: 24px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.02);
        margin-bottom: 20px;
        border: 1px solid rgba(255,255,255,0.8);
        border-bottom: 4px solid #AA7F31;
        display: flex;
        justify-content: center;
        align-items: center;
    ">
        <h2 style="margin: 0; color: #000000; font-size: 1.8rem; font-weight: 700; letter-spacing: 0.5px;">
            Control de Empleados
        </h2>
    </div>

    <div style="display: flex; justify-content: flex-end; margin-bottom: 15px;">
        <button onclick="abrirModal()" style="background-color: #AA7F31; color: white; border: none; padding: 10px 20px; border-radius: 20px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 10px rgba(170, 127, 49, 0.15);">
            <i class="fa-solid fa-user-plus"></i> Agregar Empleado
        </button>
    </div>

    <div style="border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; margin: 0;">
            <thead>
                <tr style="background-color: #124416; border-bottom: 2px solid #e2e8f0;">
                    <th style="padding: 14px; color: #ffffff;">ID</th>
                    <th style="padding: 14px; color: #ffffff;">Nombre Completo</th>
                    <th style="padding: 14px; color: #ffffff;">Fecha de Ingreso</th>
                    <th style="padding: 14px; color: #ffffff; text-align: center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($empleados as $emp)
                <tr style="border-bottom: 1px solid #f1f5f9;">
                    <td style="padding: 14px; font-weight: bold; color: #1e293b;">{{ $emp->id }}</td>
                    <td style="padding: 14px; color: #334155;">{{ $emp->nombre }} {{ $emp->apellido_paterno }} {{ $emp->apellido_materno }}</td>
                    <td style="padding: 14px; color: #64748b;">{{ \Carbon\Carbon::parse($emp->fecha_ingreso)->format('d/m/Y') }}</td>
                    <td style="padding: 14px; text-align: center;">
                        <a href="{{ route('empleados.vacaciones', $emp->id) }}" style="background-color: #124416; color: white; text-decoration: none; padding: 6px 14px; border-radius: 20px; font-size: 0.9rem; font-weight: 600; display: inline-block;">
                            <i class="fa-solid fa-calendar"></i> Vacaciones
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>

<div id="modalAgregar" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
    
    <div style="background: white; width: 100%; max-width: 500px; padding: 30px; border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); position: relative; margin: 20px;">
        
        <h3 style="margin-top: 0; color: #124416; border-bottom: 2px solid #AA7F31; padding-bottom: 10px; margin-bottom: 20px; font-size: 1.5rem;">
            <i class="fa-solid fa-user-plus"></i> Nuevo Empleado
        </h3>

        <form action="{{ route('empleados.store') }}" method="POST">
            @csrf <div style="margin-bottom: 15px;">
                <label style="display: block; color: #334155; font-weight: 600; margin-bottom: 5px;">Nombre(s)</label>
                <input type="text" name="nombre" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; box-sizing: border-box; outline: none; font-family: inherit;">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; color: #334155; font-weight: 600; margin-bottom: 5px;">Apellido Paterno</label>
                <input type="text" name="apellido_paterno" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; box-sizing: border-box; outline: none; font-family: inherit;">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; color: #334155; font-weight: 600; margin-bottom: 5px;">Apellido Materno</label>
                <input type="text" name="apellido_materno" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; box-sizing: border-box; outline: none; font-family: inherit;">
            </div>

            <div style="margin-bottom: 25px;">
                <label style="display: block; color: #334155; font-weight: 600; margin-bottom: 5px;">Fecha de Ingreso</label>
                <input type="date" name="fecha_ingreso" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; box-sizing: border-box; outline: none; font-family: inherit;">
            </div>
             <div style="margin-bottom: 25px;">
                <label style="display: block; color: #334155; font-weight: 600; margin-bottom: 5px;">Puesto</label>
                <input type="text" name="puesto" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; box-sizing: border-box; outline: none; font-family: inherit;">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="cerrarModal()" style="background: #e2e8f0; color: #475569; border: none; padding: 10px 20px; border-radius: 20px; font-weight: 600; cursor: pointer; transition: 0.2s;">
                    Cancelar
                </button>
                <button type="submit" style="background: #124416; color: white; border: none; padding: 10px 20px; border-radius: 20px; font-weight: 600; cursor: pointer; transition: 0.2s;">
                    Guardar Empleado
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function abrirModal() {
        // Muestra el modal usando Flexbox para que quede perfectamente centrado
        document.getElementById('modalAgregar').style.display = 'flex';
    }

    function cerrarModal() {
        // Oculta el modal
        document.getElementById('modalAgregar').style.display = 'none';
    }

    // Truco extra: Cerrar el modal si el usuario hace clic afuera de la caja blanca
    window.onclick = function(event) {
        var modal = document.getElementById('modalAgregar');
        if (event.target == modal) {
            cerrarModal();
        }
    }
</script>
@endsection