<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Constancia de Periodo Vacacional</title>
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
                <img src="{{ $base64 }}" alt="UCO PREPA CONTEMPORÁNEA" style="height: 160px; margin-bottom: 2px;">
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
            <td style="width: 23%; font-weight: bold;">Días pendientes por disfrutar:</td>
            <td class="cell-inline-border" style="width: 6%;">{{ $diasRestantes }}</td>
            <td style="width: 25%; padding-left: 8px;">días del Período Vacacional</td>
            <td class="cell-inline-border" style="width: 10%;">{{ $anioActual }}</td>
            <td style="width: 10%;"></td>
            <td style="width: 19%; text-align: right; font-weight: bold; padding-right: 5px;">Días con derecho:</td>
            <td class="cell-inline-border" style="width: 7%;">{{ $diasDerecho }}</td>
        </tr>
    </table>

    <div class="vacation-section-title">Último periodo vacacional disfrutado:</div>
    
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
            @php
                $ultimoPeriodo = $periodoSeleccionado ?? ($periodosVacacionales instanceof \Illuminate\Support\Collection 
                    ? $periodosVacacionales->first() 
                    : (is_array($periodosVacacionales) ? reset($periodosVacacionales) : null));
            @endphp

            @if($ultimoPeriodo && $ultimoPeriodo->fecha_inicio && $ultimoPeriodo->fecha_fin && $ultimoPeriodo->fecha_regreso)
                <tr>
                    <td>{{ $anioActual }}</td>
                    <td style="font-weight: bold;">{{ \Carbon\Carbon::parse($ultimoPeriodo->fecha_inicio)->diffInDays(\Carbon\Carbon::parse($ultimoPeriodo->fecha_fin)) + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($ultimoPeriodo->fecha_inicio)->format('d') }}</td>
                    <td>{{ \Carbon\Carbon::parse($ultimoPeriodo->fecha_inicio)->format('m') }}</td>
                    <td>{{ \Carbon\Carbon::parse($ultimoPeriodo->fecha_inicio)->format('Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($ultimoPeriodo->fecha_fin)->format('d') }}</td>
                    <td>{{ \Carbon\Carbon::parse($ultimoPeriodo->fecha_fin)->format('m') }}</td>
                    <td>{{ \Carbon\Carbon::parse($ultimoPeriodo->fecha_fin)->format('Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($ultimoPeriodo->fecha_regreso)->format('d') }}</td>
                    <td>{{ \Carbon\Carbon::parse($ultimoPeriodo->fecha_regreso)->format('m') }}</td>
                    <td>{{ \Carbon\Carbon::parse($ultimoPeriodo->fecha_regreso)->format('Y') }}</td>
                </tr>
            @else
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
            @endif
        </tbody>
    </table>

    <table class="balances-table" style="margin-bottom: 20px !important;">
        <tr>
            <td style="width: 23%; font-weight: bold;">Días restantes por disfrutar:</td>
            <td class="cell-inline-border" style="width: 6%;">{{ $diasRestantes }}</td>
            <td style="width: 25%; padding-left: 8px;">días del Período Vacacional</td>
            <td class="cell-inline-border" style="width: 10%;">{{ $anioActual }}</td>
            <td></td>
        </tr>
    </table>

    <div class="observations-title">Observaciones:</div>
    <div class="observations-box">
        {{ $ultimoPeriodo && $ultimoPeriodo->observaciones ? $ultimoPeriodo->observaciones : 'Ninguna.' }}
    </div>

    <div class="disclaimer">
        * Quedando de conformidad que no se me adeuda ningún día de vacaciones adicionales a los indicados.
    </div>

    <div class="spacer-firmas"></div>

    <div class="signatures-container">
        <div class="signatures-row">
            <div class="signature-col">
                <div class="signature-line">Acepto de Conformidad</div>
                <div class="signature-details">{{ $empleado->nombre }} {{ $empleado->apellido_paterno }} {{ $empleado->apellido_materno }}</div>
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
            <div class="signature-space-vobo"></div>
            <div class="vobo-col">
                <div class="signature-line">reviso y autorizo</div>
                <div class="signature-details">L.C. Diana Sánchez Espino</div>
                <div class="signature-details" style="color: #555;">Contralora Corporativa</div>
            </div>
            <div class="signature-space-vobo"></div>
        </div>
    </div>

</div>

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
    }

    .container {
        width: 92%;
        margin: 0 auto;
        padding-top: 20px;
        text-align: center;
    }

    .header-table, .info-table, .balances-table, .vacation-table {
        width: 100% !important;
        border-collapse: collapse;
        margin-top: 12px !important;
        margin-bottom: 12px !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
    }

    .header-table td {
        border: none;
        padding: 0;
        vertical-align: bottom;
    }

    .logo-area { width: 30%; }
    .title-area {
        width: 40%;
        text-align: center;
        font-size: 14px;
        font-weight: bold;
        letter-spacing: 0.5px;
        padding-bottom: 5px;
    }

    .date-area {
        width: 30%;
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
        padding-right: 25px;
        margin-top: 2px;
    }

    .intro-text {
        width: 100%;
        margin: 15px 0;
        font-size: 10.5px;
        text-align: justify;
    }

    .info-table td {
        border: 1px solid #000;
        padding: 6px 8px;
        font-size: 10.5px;
        text-align: left;
    }

    .info-table td.label {
        width: 15%;
        font-weight: bold;
    }

    .info-table td.value {
        width: 85%;
    }

    .balances-table td {
        border: none;
        padding: 5px 0;
        vertical-align: middle;
        font-size: 10.5px;
        text-align: left;
    }

    .cell-inline-border {
        border: 1px solid #000 !important;
        text-align: center !important;
        font-weight: bold;
        padding: 4px 8px !important;
    }

    .vacation-section-title {
        font-weight: bold;
        margin: 15px auto 5px auto;
        font-size: 10.5px;
        text-align: center;
    }

    .vacation-table th, 
    .vacation-table td {
        border: 1px solid #000;
        padding: 5px 2px;
        text-align: center;
        font-size: 10px;
    }

    .vacation-table th {
        font-weight: bold;
        background-color: #fff;
    }

    .observations-title {
        font-weight: bold;
        margin-top: 15px;
        font-size: 10.5px;
        text-align: center;
    }

    .observations-box {
        width: 100%;
        border: 1px solid #000;
        min-height: 55px;
        margin: 6px 0 12px 0;
        padding: 6px 10px;
        text-align: left;
        font-size: 10px;
        white-space: pre-wrap;
        word-wrap: break-word;
    }

    .disclaimer {
        width: 100%;
        margin: 0;
        font-size: 9px;
        text-align: justify;
    }

    /* CAMBIO RELEVANTE: Añadimos un bloque vacío con altura específica para obligar el empuje */
    .spacer-firmas {
        width: 100%;
        height: 140px; /* <--- Controla la distancia de la flecha roja. Súbelo si quieres más abajo o bájalo si se crea otra página */
    }

    .signatures-container {
        width: 100%;
        margin-top: 10px; 
    }

    .signatures-row {
        display: table;
        width: 100%;
        table-layout: fixed;
        margin: 0 auto 55px auto; 
    }

    .signature-col {
        display: table-cell;
        width: 40%;
        text-align: center;
        vertical-align: top;
    }

    .signature-space {
        width: 20%;
        display: table-cell;
    }

    .signature-line {
        border-top: 1px solid #000;
        padding-top: 4px;
        font-weight: bold;
        font-size: 10px;
        text-transform: uppercase;
    }

    .signature-details {
        font-size: 9.5px;
        margin-top: 2px;
    }

    .vobo-row {
        display: table;
        width: 100%;
        table-layout: fixed;
        margin: 0 auto;
    }

    .vobo-col {
        display: table-cell;
        width: 40%;
        text-align: center;
    }

    .signature-space-vobo {
        width: 30%;
        display: table-cell;
    }
</style>

</body>
</html>