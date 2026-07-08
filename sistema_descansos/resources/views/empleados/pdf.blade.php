<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Constancia de Periodo Vacacional</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            color: #000;
            line-height: 1.4;
            background-color: #fff;
            padding: 0;
        }

        .container {
            width: calc(100% - 16px);
            max-width: 190mm;
            margin: 0 auto;
            padding: 16px 10px 30px;
            background: white;
        }

        /* ========== HEADER ========== */
        .header {
            display: table;
            width: 100%;
            margin-bottom: 12px;
        }

        .header-cell {
            display: table-cell;
            vertical-align: top;
        }

        .logo-cell {
            width: 90px;
            text-align: left;
        }

        .logo-cell img {
            width: 90px;
            height: auto;
        }

        .title-cell {
            width: auto;
            text-align: center;
            padding: 0 10px;
            vertical-align: middle;
        }

        .title-cell h1 {
            font-size: 13px;
            font-weight: bold;
            line-height: 1.1;
            margin: 0;
        }

        .date-cell {
            width: 120px;
            text-align: right;
        }

        .date-label {
            font-size: 10px;
            margin-bottom: 2px;
            display: block;
        }

        .date-value {
            border-bottom: 1px solid #000;
            padding: 2px 6px;
            font-weight: bold;
            font-size: 10px;
            text-align: center;
            display: inline-block;
            min-width: 70px;
        }

        /* ========== INTRO TEXT ========== */
        .intro-text {
            font-size: 10px;
            text-align: justify;
            margin: 20px 0;
            line-height: 1.3;
        }

        /* ========== INFO TABLE ========== */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            border: 1px solid #000;
        }

        .info-table tr {
            border: 1px solid #000;
        }

        .info-table td {
            border: 1px solid #000;
            padding: 6px 12px;
            font-size: 10px;
        }

        .info-table .label {
            font-weight: bold;
            width: 14%;
            background-color: #f7f7f7;
        }

        .info-table .value {
            width: 86%;
        }

        /* ========== BALANCES ROW ========== */
        .balance-row {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            margin: 15px 0;
            font-size: 10px;
            gap: 12px;
            flex-wrap: wrap;
        }

        .balance-item {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
            min-width: 220px;
        }

        .balance-label {
            font-weight: bold;
            white-space: nowrap;
        }

        .balance-box {
            border: 1px solid #000;
            padding: 4px 8px;
            font-weight: bold;
            min-width: 32px;
            text-align: center;
            font-size: 11px;
            background-color: #fff;
        }

        /* ========== TABLE TITLE ========== */
        .table-title {
            font-weight: bold;
            font-size: 10px;
            margin: 25px 0 10px 0;
            text-align: center;
        }

        /* ========== VACATION TABLE ========== */
        .vacation-table {
            width: 100%;
            border-collapse: collapse;
            margin: 12px 0 25px 0;
            border: 1px solid #000;
        }

        .vacation-table th,
        .vacation-table td {
            border: 1px solid #000;
            padding: 8px 6px;
            font-size: 9px;
            text-align: center;
            line-height: 1.2;
            vertical-align: middle;
        }

        .vacation-table th {
            font-weight: bold;
            background-color: #fafafa;
        }

        .vacation-table td {
            height: 20px;
        }

        /* ========== OBSERVATIONS ========== */
        .observations-title {
            font-weight: bold;
            font-size: 10px;
            margin: 20px 0 6px 0;
        }

        .observations-box {
            border: 1px solid #000;
            padding: 10px;
            min-height: 60px;
            font-size: 10px;
            text-align: left;
            line-height: 1.4;
            background-color: #fff;
        }

        /* ========== DISCLAIMER ========== */
        .disclaimer {
            font-size: 9px;
            margin-top: 15px;
            text-align: justify;
            line-height: 1.2;
        }

       /* ========== SIGNATURES ========== */
        .signatures-section {
            margin-top: 75px;
            width: 100%;
        }

        /* Usamos tablas tradicionales compatibles con cualquier generador de PDF */
        .signatures-table {
            width: 100%;
            border-collapse: collapse;
            border: none !important; /* Quita bordes externos si los hereda */
        }

        .signatures-table td {
            border: none !important; /* Garantiza que las celdas no tengan cuadros negros */
            padding: 0;
            vertical-align: top;
            text-align: center;
        }

        .signature-line {
            width: 160px;
            border-top: 1px solid #000;
            margin: 0 auto 12px auto;
        }

        .signature-title {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .signature-name {
            font-size: 9px;
            margin-bottom: 2px;
        }

        .signature-position {
            font-size: 8px;
            color: #555;
        }
    </style>
</head>
<body>

<div class="container">
    
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
            <div class="date-value">{{ $fecha->format('d-M-y') }}</div>
        </div>
    </div>

    <!-- INTRO TEXT -->
    <div class="intro-text">
        De conformidad con el Artículo 76 de la Ley Federal del Trabajo, se extiende la presente Constancia de Período Vacacional que usted disfrutará.
    </div>

    <!-- INFO TABLE -->
    <table class="info-table">
        <tr>
            <td class="label">Nombre:</td>
            <td class="value">{{ $empleado->nombre }} {{ $empleado->apellido_paterno }} {{ $empleado->apellido_materno }}</td>
        </tr>
        <tr>
            <td class="label">Puesto:</td>
            <td class="value">{{ $puesto?->nombre ?? 'No asignado' }}</td>
        </tr>
        <tr>
            <td class="label">Área:</td>
            <td class="value">{{ $empleado->area ?? 'Mantenimiento y Seguridad' }}</td>
        </tr>
    </table>

    <!-- BALANCE 1 -->
    <div class="balance-row">
        <div class="balance-item">
            <span class="balance-label">Días pendientes por disfrutar:</span>
            <span class="balance-box">{{ $diasRestantes }}</span>
            <span>días del Período Vacacional</span>
            <span class="balance-box">2025</span>
        </div>
        <div class="balance-item">
            <span class="balance-label">Días con derecho:</span>
            <span class="balance-box">{{ $diasDerecho }}</span>
        </div>
    </div>

    <!-- TABLE TITLE -->
    <div class="table-title">Último período vacacional disfrutado:</div>

    <!-- VACATION TABLE -->
    @php
        $ultimoPeriodo = $periodoSeleccionado ?? ($periodosVacacionales instanceof \Illuminate\Support\Collection 
            ? $periodosVacacionales->first() 
            : (is_array($periodosVacacionales) ? reset($periodosVacacionales) : null));
    @endphp

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
            @if($ultimoPeriodo && $ultimoPeriodo->fecha_inicio && $ultimoPeriodo->fecha_fin)
                <tr>
                    <td>{{ $periodoVisual ?? $periodoAnio }}</td>
                    <td>{{ $ultimoPeriodo->dias ?? 0 }}</td>
                    <td>{{ \Carbon\Carbon::parse($ultimoPeriodo->fecha_inicio)->format('d') }}</td>
                    <td>{{ \Carbon\Carbon::parse($ultimoPeriodo->fecha_inicio)->format('m') }}</td>
                    <td>{{ \Carbon\Carbon::parse($ultimoPeriodo->fecha_inicio)->format('Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($ultimoPeriodo->fecha_fin)->format('d') }}</td>
                    <td>{{ \Carbon\Carbon::parse($ultimoPeriodo->fecha_fin)->format('m') }}</td>
                    <td>{{ \Carbon\Carbon::parse($ultimoPeriodo->fecha_fin)->format('Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($ultimoPeriodo->fecha_regreso ?? $ultimoPeriodo->fecha_fin->addDay())->format('d') }}</td>
                    <td>{{ \Carbon\Carbon::parse($ultimoPeriodo->fecha_regreso ?? $ultimoPeriodo->fecha_fin->addDay())->format('m') }}</td>
                    <td>{{ \Carbon\Carbon::parse($ultimoPeriodo->fecha_regreso ?? $ultimoPeriodo->fecha_fin->addDay())->format('Y') }}</td>
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
            <span class="balance-box">2025</span>
        </div>
    </div>

    @if(isset($ajustesUsados) && $ajustesUsados->count() > 0)
        @foreach($ajustesUsados as $ajuste)
            <div class="balance-row">
                <div class="balance-item">
                    <span class="balance-label">Días del periodo {{ $ajuste->anio }}:</span>
                    <span class="balance-box">{{ $ajuste->restante }}</span>
                    <span>días del Período Vacacional</span>
                    <span class="balance-box">{{ $ajuste->anio }}</span>
                </div>
            </div>
        @endforeach
    @endif

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
        
        <table class="signatures-table" style="margin-bottom: 40px;">
            <tr>
                <td style="width: 45%;">
                    <div class="signature-line"></div>
                    <div class="signature-title">ACEPTO DE CONFORMIDAD</div>
                    <div class="signature-name">Juan José Fabián Ramos</div>
                    <div class="signature-position">Intendente</div>
                </td>
                <td style="width: 10%;"></td>
                <td style="width: 45%;">
                    <div class="signature-line"></div>
                    <div class="signature-title">RECIBIDO</div>
                    <div class="signature-name">Roberto Carlos Matehuala Vargas</div>
                    <div class="signature-position">Coordinador Administrativo</div>
                </td>
            </tr>
        </table>

        <table class="signatures-table">
            <tr>
                <td style="width: 25%;"></td>
                <td style="width: 50%;">
                    <div class="signature-line"></div>
                    <div class="signature-title">REVISÓ Y AUTORIZÓ</div>
                    <div class="signature-name">L.C. Diana Sánchez Espino</div>
                    <div class="signature-position">Contralora Corporativa</div>
                </td>
                <td style="width: 25%;"></td>
            </tr>
        </table>

    </div>

</body>
</html>