<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Vacaciones - {{ $anioActual }}</title>
    <style>
        body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; font-size: 12px; }
        .title { text-align: center; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; }
        th { background: #f4f4f4; }
    </style>
</head>
<body>
    <div class="title">
        <h2>Reporte de Vacaciones - {{ $anioActual }}</h2>
        <p>Resumen de días tomados y días pendientes por empleado</p>
    </div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Empleado</th>
                <th>Puesto</th>
                <th>Fecha ingreso</th>
                <th>Días derecho</th>
                <th>Días tomados</th>
                <th>Días adeuda</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $i => $r)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $r['empleado'] }}</td>
                    <td>{{ $r['puesto'] ?? '-' }}</td>
                    <td>{{ $r['fecha_ingreso'] ?? '-' }}</td>
                    <td style="text-align: center;">{{ $r['dias_derecho'] }}</td>
                    <td style="text-align: center;">{{ $r['dias_tomados'] }}</td>
                    <td style="text-align: center;">{{ $r['dias_adeuda'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>