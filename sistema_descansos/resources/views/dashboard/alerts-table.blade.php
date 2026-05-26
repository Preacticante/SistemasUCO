<div class="stat-card table-uco-container">
    <div class="table-uco-header">
        <strong>Empleados con menos días restantes</strong>
    </div>
    
    <div style="overflow-x: auto;">
        <table class="alert-table">
            <thead>
                <tr>
                    <th>Empleado</th>
                    <th>Días restantes</th>
                    <th>Días tomados</th>
                    <th style="text-align: center;">Acción</th>
                </tr>
            </thead>
            <tbody>
                @foreach($empleadosConMenosDias as $item)
                    <tr>
                        <td>{{ $item->empleado->nombre }} {{ $item->empleado->apellido_paterno }} {{ $item->empleado->apellido_materno }}</td>
                        <td style="font-weight: 700; color: #ef4444;">{{ $item->diasRestantes }}</td>
                        <td>{{ $item->diasTomados }}</td>
                        <td style="text-align: center;">
                            <a href="{{ route('empleados.vacaciones', $item->empleado->id) }}" class="btn-calcular">Ver</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>