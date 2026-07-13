<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Constancia de Periodo Vacacional</title>
    <style>
        @page { margin: 18mm 18mm 18mm 18mm; }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Arial', sans-serif; font-size: 11px; color: #000; line-height: 1.35; background:#fff; margin:0; }

        .container { width: 100%; background: #fff; padding: 0; }

        /* HEADER */
        .header { display: table; width: 100%; margin-bottom: 12px; }
        .header-cell { display: table-cell; vertical-align: middle; }

        .logo-cell { width: 160px; padding-top: 2px; }
        .logo-cell img { width: 150px; height: auto; display: block; }

        .title-cell { text-align: center; vertical-align: middle; padding-top:6px; }
        .title-cell h1 { font-size: 15px; font-weight: 700; letter-spacing: 0.6px; line-height: 1.05; }

        .date-cell { width: 120px; text-align: right; vertical-align: top; }
        .date-label { font-size: 9px; color: #000; margin-bottom: 4px; display:block; }
        .date-value { display:inline-block; border-bottom:1px solid #000; padding:4px 8px; font-weight:700; font-size:11px; min-width:72px; text-align:center; }

        /* INTRO TEXT */
        .intro-text { font-size:11px; text-align:justify; margin: 12px 0; line-height:1.45; }

        /* INFO TABLE */
        .info-table { width:100%; border-collapse: collapse; margin-bottom: 12px; }
        .info-table td { border:1px solid #000; padding:10px 12px; font-size:11px; vertical-align:middle; }
        .info-table td.label { width:14%; font-weight:700; }
        .info-table td.value { width:86%; }

        /* BALANCE ROW - usar table en lugar de flex */
        .balance-row { display: table; width:100%; margin: 10px 0; }
        .balance-item { display: table-cell; vertical-align: middle; padding: 8px 6px; font-size:11px; }
        .balance-label { font-weight:700; margin-right: 8px; }
        .balance-box { border:1px solid #000; padding:6px 10px; font-weight:700; min-width:40px; text-align:center; display: inline-block; margin: 0 6px; }

        /* TABLE TITLE */
        .table-title { text-align:center; font-weight:700; margin:10px 0 8px 0; font-size:11px; }

        /* VACATION TABLE */
        .vacation-table { width:100%; border-collapse: collapse; margin-bottom: 12px; }
        .vacation-table th, .vacation-table td { border:1px solid #000; padding:8px 4px; font-size:10px; text-align:center; }
        .vacation-table th { font-weight:700; }

        /* OBSERVATIONS */
        .observations-title { font-weight:700; font-size:11px; margin-top:8px; margin-bottom: 6px; }
        .observations-box { border:1px solid #000; padding:12px; min-height:80px; font-size:11px; text-align:left; line-height:1.4; }

        /* DISCLAIMER */
        .disclaimer { font-size:10px; margin-top:10px; text-align:justify; color:#000; }

        /* SIGNATURES */
        .signatures-section { margin-top: 40px; }
        .signatures-row { display:table; width:100%; table-layout:fixed; }
        .signature-col { display:table-cell; width:33.33%; text-align:center; vertical-align:top; padding: 0 4px; }
        .signature-line { border-top:1px solid #000; height:60px; margin-bottom:10px; width:85%; margin-left:auto; margin-right:auto; }
        .signature-title { font-weight:700; font-size:11px; text-transform:uppercase; margin-bottom: 4px; }
        .signature-name { font-size:10px; margin-bottom: 2px; }
        .signature-position { font-size:9px; color:#333; }
    </style>
</head>
<body>

<div class="container">
    
    @php
        // Normalizar fecha
        $fechaObj = isset($fecha) ? (is_string($fecha) ? \Carbon\Carbon::parse($fecha) : $fecha) : \Carbon\Carbon::now();

        // Obtener último periodo
        $ultimoPeriodo = $periodoSeleccionado ?? ($periodosVacacionales instanceof \Illuminate\Support\Collection 
            ? $periodosVacacionales->first() 
            : (is_array($periodosVacacionales) ? reset($periodosVacacionales) : null));

        // Normalizar fechas del periodo
        $inicio = null;
        $fin = null;
        $regreso = null;

        if ($ultimoPeriodo) {
            if (!empty($ultimoPeriodo->fecha_inicio)) {
                try {
                    $inicio = \Carbon\Carbon::parse($ultimoPeriodo->fecha_inicio);
                } catch (\Throwable $e) {
                    $inicio = null;
                }
            }
            if (!empty($ultimoPeriodo->fecha_fin)) {
                try {
                    $fin = \Carbon\Carbon::parse($ultimoPeriodo->fecha_fin);
                } catch (\Throwable $e) {
                    $fin = null;
                }
            }
            if (!empty($ultimoPeriodo->fecha_regreso)) {
                try {
                    $regreso = \Carbon\Carbon::parse($ultimoPeriodo->fecha_regreso);
                } catch (\Throwable $e) {
                    $regreso = null;
                }
            } elseif ($fin) {
                $regreso = $fin->copy()->addDay();
            }
        }
    @endphp
    
    <!-- HEADER -->
    <div class="header">
        <div class="header-cell logo-cell">
            @php
                $path = public_path('img/logo_uco.png');
                if (file_exists($path)) {
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                } else {
                    $base64 = '';
                }
            @endphp
            @if($base64)
                <img src="{{ $base64 }}" alt="UCO">
            @endif
        </div>
        <div class="header-cell title-cell">
            <h1>CONSTANCIA DE PERIODO<br>VACACIONAL</h1>
        </div>
        <div class="header-cell date-cell">
            <div class="date-label">Fecha: _______________</div>
                <div class="date-value">{{ isset($fechaObj) ? $fechaObj->format('d-M-Y') : (isset($fecha) ? (is_string($fecha) ? \Carbon\Carbon::parse($fecha)->format('d-M-Y') : $fecha->format('d-M-Y')) : \Carbon\Carbon::now()->format('d-M-Y')) }}</div>
        </div>
    </div>

    <!-- INTRO TEXT -->
    <div class="intro-text">
        De conformidad con el Artículo 76 de la Ley Federal del Trabajo, se extiende la presente Constancia de Período Vacacional que usted disfrutará.
    </div>

    <!-- INFO TABLE -->
    <style>
        /* Página A4: usar @page para que dompdf respete márgenes */
        @page { margin: 18mm 18mm 18mm 18mm; }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Arial', sans-serif; font-size: 11px; color: #000; line-height: 1.35; background:#fff; margin:0; }

        /* Contenedor principal: usar 100% y confiar en @page para márgenes */
        .container { width: 100%; background: #fff; padding: 0; }
        .content { padding: 6px 4px; }

        /* HEADER: usar display:table para compatibilidad y control preciso */
        .header { display: table; width: 100%; margin-bottom: 12px; }
        .header-cell { display: table-cell; vertical-align: middle; }

        .logo-cell { width: 160px; padding-top: 2px; }
        .logo-cell img { width: 150px; height: auto; display: block; }

        .title-cell { text-align: center; vertical-align: middle; padding-top:6px; }
        .title-cell h1 { font-size: 15px; font-weight: 700; letter-spacing: 0.6px; line-height: 1.05; }

        .date-cell { width: 120px; text-align: right; vertical-align: top; }
        .date-label { font-size: 9px; color: #000; margin-bottom: 4px; display:block; }
        .date-value { display:inline-block; border-bottom:1px solid #000; padding:4px 8px; font-weight:700; font-size:11px; min-width:72px; text-align:center; }

        .intro-text { font-size:11px; text-align:justify; margin: 12px 0; line-height:1.45; }

        /* Info (Nombre, puesto, area) con bordes y mayor padding */
        .info-table { width:100%; border-collapse: collapse; margin-bottom: 12px; }
        .info-table td { border:1px solid #000; padding:10px 12px; font-size:11px; vertical-align:middle; }
        .info-table td.label { width:14%; font-weight:700; }
        .info-table td.value { width:86%; }

        /* Reemplazar flex por layout tipo tabla para consistencia en dompdf */
        .balance-row { display: table; width:100%; margin: 8px 0; }
        .balance-item { display: table-cell; vertical-align: middle; padding: 6px 4px; font-size:11px; }
        .balance-item .balance-box { border:1px solid #000; padding:6px 10px; font-weight:700; min-width:40px; text-align:center; margin-left:8px; }

        .table-title { text-align:center; font-weight:700; margin:10px 0 6px 0; font-size:11px; }

        /* Tabla de periodo vacacional: aumentar padding y altura de fila */
        .vacation-table { width:100%; border-collapse: collapse; margin-bottom: 12px; }
        .vacation-table th, .vacation-table td { border:1px solid #000; padding:8px 6px; font-size:10px; text-align:center; }
        .vacation-table th { font-weight:700; }

        .observations-title { font-weight:700; font-size:11px; margin-top:8px; }
        .observations-box { border:1px solid #000; padding:10px; min-height:80px; font-size:11px; text-align:left; line-height:1.4; }

        .disclaimer { font-size:10px; margin-top:8px; text-align:justify; color:#000; }

        /* Firmas: colocar en la parte inferior con espaciado fijo y mayor separación */
        .signatures-section { margin-top: 36px; }
        .signatures-row { display:table; width:100%; table-layout:fixed; margin-top:6px; }
        .signature-col { display:table-cell; width:33.33%; text-align:center; vertical-align:top; padding-top:6px; }
        .signature-line { border-top:1px solid #000; height:54px; margin-bottom:8px; width:80%; margin-left:auto; margin-right:auto; }
        .signature-title { font-weight:700; font-size:11px; text-transform:uppercase; }
        .signature-name { font-size:10px; margin-top:4px; }
        .signature-position { font-size:9px; color:#666; }
    </style>
    </style>


    <table class="vacation-table">
        <thead>
            <tr>
                <th rowspan="2">Período Vacacional</th>
                <th rowspan="2">Días</th>
                <th colspan="3">Inicia</th>
                <th colspan="3">Termina</th>
                <th colspan="3">Se presenta</th>
            </tr>
            <tr>
                <th>Día</th>
                <th>Mes</th>
                <th>Año</th>
                <th>Día</th>
                <th>Mes</th>
                <th>Año</th>
                <th>Día</th>
                <th>Mes</th>
                <th>Año</th>
            </tr>
        </thead>
        <tbody>
            @if($inicio && $fin)
                <tr>
                    <td>{{ $ultimoPeriodo->anio_calendario ?? $anioActual }}</td>
                    <td>{{ $ultimoPeriodo->dias ?? 0 }}</td>
                    <td>{{ $inicio->format('d') }}</td>
                    <td>{{ $inicio->format('m') }}</td>
                    <td>{{ $inicio->format('Y') }}</td>
                    <td>{{ $fin->format('d') }}</td>
                    <td>{{ $fin->format('m') }}</td>
                    <td>{{ $fin->format('Y') }}</td>
                    <td>{{ $regreso ? $regreso->format('d') : '--' }}</td>
                    <td>{{ $regreso ? $regreso->format('m') : '--' }}</td>
                    <td>{{ $regreso ? $regreso->format('Y') : '--' }}</td>
                </tr>
            @else
                <tr>
                    <td>{{ $anioActual }}</td>
                    <td>0</td>
                    <td>--</td>
                    <td>--</td>
                    <td>--</td>
                    <td>--</td>
                    <td>--</td>
                    <td>--</td>
                    <td>--</td>
                    <td>--</td>
                    <td>--</td>
                </tr>
            @endif
        </tbody> 
    </table>

    <!-- BALANCE 2 -->
    <div class="balance-row">
        <div class="balance-item">
            <span class="balance-label">Días restantes por disfrutar:</span>
            <span class="balance-box">{{ $diasRestantes }}</span>
            <span>días del Período Vacacional</span>
            <span class="balance-box">{{ $anioActual }}</span>
        </div>
    </div>

    <!-- OBSERVATIONS -->
    <div class="observations-title">Observaciones:</div>
    <div class="observations-box">
        {{ $ultimoPeriodo && $ultimoPeriodo->observaciones ? $ultimoPeriodo->observaciones : 'Ninguna.' }}
    </div>

    <!-- DISCLAIMER -->
    <div class="disclaimer">
        * Quedando de conformidad que no se me adeuda ningún día de vacaciones adicionales a los indicados.
    </div>

    <!-- SIGNATURES -->
    <div class="signatures-section">
        <div class="signatures-row">
            <div class="signature-col">
                <div class="signature-line"></div>
                <div class="signature-title">ACEPTO DE CONFORMIDAD</div>
                <div class="signature-name">{{ $empleado->nombre }} {{ $empleado->apellido_paterno }} {{ $empleado->apellido_materno }}</div>
                <div class="signature-position">{{ $puesto?->nombre ?? 'Puesto' }}</div>
            </div>
            <div class="signature-col">
                <div class="signature-line"></div>
                <div class="signature-title">RECIBIDO</div>
                <div class="signature-name">Roberto Carlos Matehuala Vargas</div>
                <div class="signature-position">Coordinador Administrativo</div>
            </div>
            <div class="signature-col">
                <div class="signature-line"></div>
                <div class="signature-title">REVISO Y AUTORIZO</div>
                <div class="signature-name">L.C. Diana Sánchez Espino</div>
                <div class="signature-position">Contralora Corporativa</div>
            </div>
        </div>
    </div>

</div>

</body>
</html>