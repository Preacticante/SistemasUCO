@extends('layouts.app')

@section('title', 'Empleados')
@section('header', 'Directorio de Personal')

@section('content')
<div style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; border-bottom: 2px solid #f3f4f6; padding-bottom: 15px;">
        <h2 style="margin: 0; color: #1e293b;">Control de Empleados</h2>
        <button style="background-color: #10b981; color: white; border: none; padding: 10px 20px; border-radius: 6px; font-weight: 600; cursor: pointer;">
            <i class="fa-solid fa-user-plus"></i> Agregar Empleado
        </button>
    </div>

    <table style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr style="background-color: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                <th style="padding: 14px; color: #64748b;">ID</th>
                <th style="padding: 14px; color: #64748b;">Nombre Completo</th>
                <th style="padding: 14px; color: #64748b;">Fecha de Ingreso</th>
                <th style="padding: 14px; color: #64748b; text-align: center;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($empleados as $emp)
            <tr style="border-bottom: 1px solid #f1f5f9;">
                <td style="padding: 14px; font-weight: bold; color: #1e293b;">{{ $emp->id }}</td>
                <td style="padding: 14px; color: #334155;">{{ $emp->nombre }} {{ $emp->apellido_paterno }} {{ $emp->apellido_materno }}</td>
                <td style="padding: 14px; color: #64748b;">{{ \Carbon\Carbon::parse($emp->fecha_ingreso)->format('d/m/Y') }}</td>
                <td style="padding: 14px; text-align: center;">
                    <a href="{{ route('empleados.vacaciones', $emp->id) }}" style="background-color: #3b82f6; color: white; text-decoration: none; padding: 6px 14px; border-radius: 4px; font-size: 0.9rem; font-weight: 500; margin-right: 5px;">
                        <i class="fa-solid fa-calendar"></i> Vacaciones
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection