<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte Ejecutivo de Vacaciones - {{ $anioActual }}</title>
    <style>
        body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; font-size: 12px; color:#222 }
        .header { text-align:center; margin-bottom:12px }
        table { width:100%; border-collapse: collapse; margin-bottom:10px }
        th, td { border:1px solid #ddd; padding:6px 8px; }
        th { background:#f3f3f3; font-weight:700 }
        .small { font-size:11px; color:#555 }
        .totals { font-weight:700 }
        .alerts { margin-top:10px }
    </style>
</head>
<body>
    <div class="header">
        <h2>Reporte Ejecutivo de Vacaciones - {{ $anioActual }}</h2>
        <div class="small">Generado: {{ $fechaReporte ?? now()->format('d/m/Y H:i') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Empleado</th>
                <th>Puesto</th>
                <th>Antigüedad (años)</th>
                <th>Días derecho</th>
                <th>Días tomados</th>
                <th>Días restantes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($resumenPlantilla as $i => $r)
                <tr>
                    <td style="width:40px">{{ $i + 1 }}</td>
                    <td>{{ $r['nombre'] }}</td>
                    <td>{{ $r['puesto'] }}</td>
                    <td style="text-align:center">{{ $r['antiguedad'] }}</td>
                    <td style="text-align:center">{{ $r['derecho'] }}</td>
                    <td style="text-align:center">{{ $r['tomados'] }}</td>
                    <td style="text-align:center">{{ $r['restantes'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="alerts">
        <h4>Alertas (<= 2 días restantes)</h4>
        @if(count($listaAlertas) === 0)
            <div class="small">No hay alertas.</div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Empleado</th>
                        <th>Puesto</th>
                        <th>Días restantes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($listaAlertas as $a)
                        <tr>
                            <td>{{ $a['nombre'] }}</td>
                            <td>{{ $a['puesto'] }}</td>
                            <td style="text-align:center">{{ $a['restantes'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

</body>
</html>