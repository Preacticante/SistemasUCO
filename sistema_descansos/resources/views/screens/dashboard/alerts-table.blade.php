<div class="stat-card" style="margin-bottom: 24px;">
    <strong>Empleados con menos días restantes</strong>
    <table class="alert-table">
        <thead>
            <tr>
                <th>Empleado</th>
                <th>Días restantes</th>
                <th>Días tomados</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach($empleadosConMenosDias as $item)
                <tr>
                    <td>{{ $item->empleado->nombre_completo }}</td>
                    <td>{{ $item->diasRestantes }}</td>
                    <td>{{ $item->diasTomados }}</td>
                    <td><a href="{{ route('empleados.vacaciones', $item->empleado->id) }}" class="btn-calcular">Ver</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
