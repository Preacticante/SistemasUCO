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
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #000;
            line-height: 1.3;
            background-color: #fff;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 15px 20px;
            text-align: center;
        }

        .header-table {
            width: 95%;
            border-collapse: collapse;
            margin: 0 auto 12px auto;
        }

        .header-table td {
            border: none;
            padding: 0;
            vertical-align: bottom;
        }

        .logo-area { width: 30%; }
        .logo-main { font-size: 26px; font-weight: 900; color: #4A148C; letter-spacing: -1px; line-height: 1; }
        .logo-sub { font-size: 8px; font-weight: bold; color: #E65100; letter-spacing: 0.5px; margin-top: -2px; }
        .logo-slogan { font-size: 6px; color: #757575; font-style: italic; }

        .title-area {
            width: 45%;
            text-align: center;
            font-size: 15px;
            font-weight: bold;
            letter-spacing: 0.5px;
            padding-bottom: 5px;
        }

        .date-area {
            width: 25%;
            text-align: right;
            font-size: 10px;
        }

        .date-box {
            display: inline-block;
            border-bottom: 1px solid #000;
            width: 100px;
            text-align: center;
            font-weight: bold;
        }

        .date-format {
            font-size: 8px;
            color: #9e9e9e;
            padding-right: 30px;
            margin-top: 2px;
        }

        .intro-text {
            font-size: 10.5px;
            margin: 15px 0 12px 0;
            text-align: justify;
            display: block;
        }

        .info-table {
            width: 95%;
            border-collapse: collapse;
            margin: 0 auto 12px auto;
        }

        .info-table td {
            border: 1px solid #000;
            padding: 5px 6px;
            font-size: 10.5px;
        }

        .info-table td.label {
            width: 15%;
            font-weight: bold;
        }

        .info-table td.value {
            width: 85%;
        }

        .balances-table {
            width: 95%;
            border-collapse: collapse;
            margin: 8px auto 15px auto;
        }

        .balances-table td {
            border: none;
            padding: 3px 0;
            vertical-align: middle;
            font-size: 10.5px;
        }

        .cell-inline-border {
            border: 1px solid #000 !important;
            text-align: center;
            font-weight: bold;
            padding: 4px 10px !important;
        }

        .vacation-section-title {
            font-weight: bold;
            margin-bottom: 6px;
            font-size: 10.5px;
            text-align: center;
        }

        .vacation-table {
            width: 95%;
            border-collapse: collapse;
            margin: 8px auto 15px auto;
        }

        .vacation-table th, 
        .vacation-table td {
            border: 1px solid #000;
            padding: 4px 2px;
            text-align: center;
            font-size: 10px;
        }

        .vacation-table th {
            font-weight: bold;
            background-color: #fff;
        }

        .observations-title {
            font-weight: bold;
            margin-bottom: 3px;
            font-size: 10.5px;
            text-align: center;
        }

        .observations-box {
            width: 95%;
            border: 1px solid #000;
            height: 50px;
            margin: 8px auto 12px auto;
        }

        .disclaimer {
            font-size: 9px;
            margin-bottom: 35px;
            text-align: justify;
        }

        .signatures-container {
            width: 100%;
            margin-top: 25px;
        }

        .signatures-row {
            display: table;
            width: 95%;
            table-layout: fixed;
            margin: 0 auto 25px auto;
        }

        .signature-col {
            display: table-cell;
            width: 38%;
            text-align: center;
            vertical-align: top;
        }

        .signature-space {
            width: 24%;
            display: table-cell;
        }

        .signature-line {
            border-top: 1px solid #000;
            padding-top: 3px;
            font-weight: bold;
            font-size: 10px;
        }

        .signature-details {
            font-size: 9.5px;
            margin-top: 1px;
        }

        .vobo-row {
            display: table;
            width: 95%;
            table-layout: fixed;
            margin: 0 auto;
        }

        .vobo-col {
            display: table-cell;
            width: 38%;
            margin: 0 auto;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">

    <table class="header-table">
        <tr>
            <td class="logo-area" style="text-align: left;">
                
                
        @php
            $path = public_path('img/logo_uco.png');
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        @endphp

<img src="{{ $base64 }}" 
     alt="UCO PREPA CONTEMPORÁNEA"
     style="height: 100px; margin-bottom: 2px;">


            </td>
            <td class="title-area">
                CONSTANCIA DE PERIODO VACACIONAL
            </td>
            <td class="date-area">
                Fecha: <span class="date-box">{{ $fecha->format('d-M-y') }}</span>
                <div class="date-format">dd/mm/aa</div>
            </td>
        </tr>
    </table>

    <div class="intro-text">
        De conformidad con el Artículo 76 de la Ley Federal del Trabajo, se extiende la presente Constancia de Periodo Vacacional que usted disfrutará.
    </div>

    <table class="info-table">
        <tr>
            <td class="label">Nombre:</td>
            <td class="value">{{ $empleado->nombre }} {{ $empleado->apellido_paterno }} {{ $empleado->apellido_materno }}</td>
        </tr>
        <tr>
            <td class="label">Puesto:</td>
            <td class="value">{{ $puesto?->nombre ?? 'Ayudante General' }}</td>
        </tr>
        <tr>
            <td class="label">Área:</td>
            <td class="value">{{ $empleado->area ?? 'Mantenimiento y Seguridad' }}</td>
        </tr>
    </table>

    <table class="balances-table">
        <tr>
            <td style="width: 22%; font-weight: bold;">Días pendientes por disfrutar:</td>
            <td class="cell-inline-border" style="width: 6%;">{{ $diasRestantes }}</td>
            <td style="width: 25%; padding-left: 8px;">días del Período Vacacional</td>
            <td class="cell-inline-border" style="width: 10%;">{{ $anioActual }}</td>
            <td style="width: 12%;"></td>
            <td style="width: 18%; text-align: right; font-weight: bold;">Días con derecho:</td>
            <td class="cell-inline-border" style="width: 7%;">{{ $diasDerecho }}</td>
        </tr>
    </table>

    <div class="vacation-section-title">Días a disfrutar:</div>
    <table class="vacation-table">
        <thead>
            <tr>
                <th rowspan="2" style="width: 20%;">Periodo Vacacional</th>
                <th rowspan="2" style="width: 10%;">Días</th>
                <th colspan="3" style="width: 23.33%;">Inicia</th>
                <th colspan="3" style="width: 23.33%;">Termina</th>
                <th colspan="3" style="width: 23.33%;">Se presenta</th>
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
            @forelse($periodosVacacionales as $periodo)
                <tr>
                    <td>{{ $anioActual }}</td>
                    <td style="font-weight: bold;">{{ \Carbon\Carbon::parse($periodo->fecha_inicio)->diffInDays(\Carbon\Carbon::parse($periodo->fecha_fin)) + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($periodo->fecha_inicio)->format('d') }}</td>
                    <td>{{ \Carbon\Carbon::parse($periodo->fecha_inicio)->format('m') }}</td>
                    <td>{{ \Carbon\Carbon::parse($periodo->fecha_inicio)->format('Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($periodo->fecha_fin)->format('d') }}</td>
                    <td>{{ \Carbon\Carbon::parse($periodo->fecha_fin)->format('m') }}</td>
                    <td>{{ \Carbon\Carbon::parse($periodo->fecha_fin)->format('Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($periodo->fecha_regreso)->format('d') }}</td>
                    <td>{{ \Carbon\Carbon::parse($periodo->fecha_regreso)->format('m') }}</td>
                    <td>{{ \Carbon\Carbon::parse($periodo->fecha_regreso)->format('Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td>{{ $anioActual }}</td>
                    <td style="font-weight: bold;">{{ $diasTomados }}</td>
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
            @endforelse
        </tbody>
    </table>

    <table class="balances-table" style="margin-bottom: 25px;">
        <tr>
            <td style="width: 22%; font-weight: bold;">Días restantes por disfrutar:</td>
            <td class="cell-inline-border" style="width: 6%;">{{ $diasRestantes }}</td>
            <td style="width: 25%; padding-left: 8px;">días del Período Vacacional</td>
            <td class="cell-inline-border" style="width: 10%;">{{ $anioActual }}</td>
            <td></td>
        </tr>
    </table>

    <div class="observations-title">Observaciones:</div>
    <div class="observations-box"></div>

    <div class="disclaimer">
        * Quedando de conformidad que no se me adeuda ningún día de vacaciones adicionales a los indicados.
    </div>

    <div class="signatures-container">
        <div class="signatures-row">
            <div class="signature-col">
                <div class="signature-line">Acepto de Conformidad</div>
                <div class="signature-details">{{ $empleado->nombre_completo }}</div>
                <div class="signature-details" style="color: #555;">{{ $puesto?->nombre ?? 'Ayudante General' }}</div>
            </div>
            <div class="signature-space"></div>
            <div class="signature-col">
                <div class="signature-line">Recibido</div>
                <div class="signature-details">Roberto Carlos Matehuala Vargas</div>
                <div class="signature-details" style="color: #555;">Coordinador Administrativo</div>
            </div>
        </div>
        
        <div class="vobo-row">
            <div class="signature-space" style="width: 31%;"></div>
            <div class="vobo-col">
                <div class="signature-line">VoBo</div>
                <div class="signature-details">L.C. Diana Sánchez Espino</div>
                <div class="signature-details" style="color: #555;">Contralora Corporativa</div>
            </div>
            <div class="signature-space" style="width: 31%;"></div>
        </div>
    </div>

</div>

</body>
</html>
