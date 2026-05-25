<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre Completo</th>
            <th>Fecha de Ingreso</th>
            <th>Vacaciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($empleados as $empleado)
        <tr>
            <td>{{ $empleado->id }}</td>
            <td>{{ $empleado->nombre_completo }}</td>
            <td>{{ $empleado->fecha_ingreso }}</td>
            <td>
                <a href="{{ route('empleados.vacaciones', $empleado->id) }}" class="btn-calcular">Vacaciones</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
