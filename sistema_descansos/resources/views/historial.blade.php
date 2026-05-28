@extends('layouts.app')

@section('title', 'Historial')
@section('header', 'Historial de Vacaciones')

@section('content')
    <div class="panel-principal-header">
        <h2>Panel Principal</h2>
        <p>Resumen general del estado de vacaciones y alertas de personal activo.</p>
    </div>

    <div class="table-card-container">
        <div class="table-card-header">
            Empleados con menos días restantes
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
                    @forelse($periodosVacacionales as $periodo)
                        @php
                            $empleado = $periodo->empleado;
                            $fechaFin = \Carbon\Carbon::parse($periodo->fecha_fin);
                            $estado = $fechaFin->isPast() ? 'Tomado' : 'Programado';
                        @endphp
                        <tr>
                            <td class="text-employee-name">
                                {{ $empleado?->nombre }} {{ $empleado?->apellido_paterno }} {{ $empleado?->apellido_materno }}
                            </td>
                            <td style="color: #64748b; font-weight: 500;">
                                Vacaciones
                            </td>
                            <td style="color: #334155;">
                                {{ \Carbon\Carbon::parse($periodo->fecha_inicio)->format('d/m/Y') }}
                            </td>
                            <td style="color: #334155;">
                                {{ $fechaFin->format('d/m/Y') }}
                            </td>
                            <td class="{{ $fechaFin->isPast() ? 'text-muted-days' : 'text-danger-bold' }}">
                                {{ $periodo->dias }} día{{ $periodo->dias === 1 ? '' : 's' }}
                            </td>
                            <td>
                                <span class="badge {{ $fechaFin->isPast() ? 'badge-success' : 'badge-info' }}">
                                    {{ $estado }}
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <a href="#" class="btn-action-ver">Ver</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center; padding: 25px 0; color: #5e7087;">
                                No existen solicitudes de periodos vacacionales en el sistema.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
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
            background-color: #a87e3b; /* Remate inferior dorado */
        }
        .panel-principal-header h2 {
            margin: 0 0 8px 0;
            color: #2b0b4d; /* Tipografía Morada Corporativa */
            font-size: 1.8rem;
            font-weight: 700;
        }
        .panel-principal-header p {
            margin: 0;
            color: #5e7087;
            font-size: 0.95rem;
        }

        /* Envoltorio con bordes redondeados para simular tarjetas corporativas */
        .table-card-container {
            background: white;
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
            color: #124416; /* Encabezados de columnas color morado */
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

        /* Estilos aplicados a los textos de las celdas */
        .text-employee-name {
            color: #334155;
            font-weight: 600;
        }
        .text-danger-bold {
            color: #ef4444; 
            font-weight: 700;
        }
        .text-muted-days {
            color: #8293a6; 
            font-weight: 700;
        }

        /* Botón de acción Ovalado café claro / dorado */
        .btn-action-ver {
            display: inline-block;
            background-color: #a87e3b;
            color: white;
            text-decoration: none;
            padding: 6px 28px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            transition: background-color 0.2s;
        }
        .btn-action-ver:hover {
            background-color: #916b30;
        }

        /* Badges e indicadores visuales de estado */
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