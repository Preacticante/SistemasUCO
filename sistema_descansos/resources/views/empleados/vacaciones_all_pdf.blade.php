<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Vacaciones - Resumen {{ $anio }}</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #444; padding: 4px; text-align: left; }
        th { background: #eee; }
        .small { font-size: 11px }
    </style>
</head>
<body>
    <h2>Resumen de Vacaciones - Año {{ $anio }}</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Puesto</th>
                <th>Fecha ingreso</th>
                <th>Antig.</th>
                <th>Días derecho</th>
                @foreach($meses as $m)
                    <th class="small">{{ substr($m,0,3) }}</th>
                @endforeach
                <th>Total tomados</th>
                <th>Restantes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $i => $r)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $r['empleado']->nombre }} {{ $r['empleado']->apellido_paterno }} {{ $r['empleado']->apellido_materno }}</td>
                    <td>{{ $r['empleado']->puesto?->nombre ?? '-' }}</td>
                    <td>{{ $r['empleado']->fecha_ingreso }}</td>
                    <td style="text-align:center">{{ $r['antiguedad'] }}</td>
                    <td style="text-align:center">{{ $r['dias_derecho'] }}</td>
                    @foreach($r['registroPorMes'] as $m)
                        <td style="text-align:center">{{ $m }}</td>
                    @endforeach
                    <td style="text-align:center">{{ $r['diasTomados'] }}</td>
                    <td style="text-align:center">{{ $r['diasRestantes'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>