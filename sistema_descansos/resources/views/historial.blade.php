@extends('layouts.app')

@section('title', 'Historial')
@section('header', 'Historial de Vacaciones')

@section('content')
    <div style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
        <h2>Registro de Descansos</h2>
        <p>Aquí se muestra la bitácora de los periodos vacacionales registrados en la base de datos.</p>
    </div>

    <div style="overflow-x: auto; margin-top: 20px;">
        <table class="responsive-table">
            <thead>
                <tr>
                    <th>Empleado</th>
                    <th>Tipo</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Días Totales</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($periodosVacacionales as $periodo)
                    @php
                        $empleado = $periodo->empleado;
                        $fechaFin = \Carbon\Carbon::parse($periodo->fecha_fin);
                        $estado = $fechaFin->isPast() ? 'Tomado' : 'Programado';
                        $badgeClass = $fechaFin->isPast() ? 'badge-success' : 'badge-info';
                    @endphp
                    <tr>
                        <td>{{ $empleado?->nombre }} {{ $empleado?->apellido_paterno }} {{ $empleado?->apellido_materno }}</td>
                        <td>Vacaciones</td>
                        <td>{{ \Carbon\Carbon::parse($periodo->fecha_inicio)->format('d/m/Y') }}</td>
                        <td>{{ $fechaFin->format('d/m/Y') }}</td>
                        <td>{{ $periodo->dias }} día{{ $periodo->dias === 1 ? '' : 's' }}</td>
                        <td><span class="badge {{ $badgeClass }}">{{ $estado }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center; padding: 18px 0; color: #475569;">No hay registros de vacaciones en la base de datos.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <style>
        .table-container {
            background: white; 
            padding: 30px; 
            border-radius: 12px; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .header-section {
            margin-bottom: 25px;
        }
        .header-section h2 {
            margin: 0 0 8px 0;
            color: #1f324f;
        }
        .header-section p {
            margin: 0;
            color: #64748b;
        }
        .responsive-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 0.95rem;
        }
        .responsive-table thead tr {
            background-color: #f8fafc;
            color: #1e293b;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #e2e8f0;
        }
        .responsive-table th, .responsive-table td {
            padding: 14px 16px;
        }
        .responsive-table tbody tr {
            border-bottom: 1px solid #f1f5f9;
            transition: background-color 0.2s;
        }
        .responsive-table tbody tr:hover {
            background-color: #f8fafc;
        }
        /* Estilos para las etiquetas de estado (Badge) */
        .badge {
            padding: 6px 12px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 500;
            display: inline-block;
        }
        .badge-success {
            background-color: #dcfce7;
            color: #15803d;
        }
        .badge-info {
            background-color: #e0f2fe;
            color: #0369a1;
        }
    </style>
@endsection