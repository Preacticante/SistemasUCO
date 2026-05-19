<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; margin: 0; padding: 20px; color: #111; }
        h1, h2 { margin: 0; }
        .header { text-align: center; margin-bottom: 20px; }
        .small { font-size: 0.9rem; color: #555; }
        .section { margin-top: 18px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 8px 10px; }
        th { background: #f3f3f3; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Vacaciones</h1>
        <p class="small">Año {{ $anioActual }} — {{ $empleado->nombre_completo }}</p>
    </div>

    <div class="section">
        <h2>Datos del empleado</h2>
        <p><strong>Nombre:</strong> {{ $empleado->nombre_completo }}</p>
        <p><strong>Fecha de ingreso:</strong> {{ $empleado->fecha_ingreso }}</p>
        <p><strong>Años trabajados:</strong> {{ $antiguedadAnios }}</p>
        <p><strong>Días de derecho:</strong> {{ $diasDerecho }}</p>
    </div>

    <div class="section">
        <h2>Resumen anual</h2>
        <p><strong>Días tomados:</strong> {{ $diasTomados }}</p>
        <p><strong>Días restantes:</strong> {{ $diasRestantes }}</p>
    </div>

    <div class="section">
        <h2>Detalle mensual</h2>
        <table>
            <thead>
                <tr>
                    <th>Mes</th>
                    <th>Días tomados</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($meses as $numero => $nombre)
                    <tr>
                        <td>{{ $nombre }}</td>
                        <td>{{ $registros->firstWhere('mes', $numero)?->dias_tomados ?? 0 }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
