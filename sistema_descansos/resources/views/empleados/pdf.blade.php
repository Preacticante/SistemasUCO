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
        }

        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px 30px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
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
            margin: 20px 0 15px 0;
            text-align: left;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .info-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            font-size: 11px;
        }

        .info-table td.label {
            width: 15%;
            font-weight: bold;
        }

        .info-table td.value {
            width: 85%;
        }

        .balances-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 20px;
        }

        .balances-table td {
            border: none;
            padding: 4px 0;
            vertical-align: middle;
        }

        .cell-inline-border {
            border: 1px solid #000 !important;
            text-align: center;
            font-weight: bold;
            padding: 4px 10px !important;
        }

        .vacation-section-title {
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 11px;
        }

        .vacation-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .vacation-table th, 
        .vacation-table td {
            border: 1px solid #000;
            padding: 5px 2px;
            text-align: center;
            font-size: 11px;
        }

        .vacation-table th {
            font-weight: bold;
            background-color: #fff;
        }

        .observations-title {
            font-weight: bold;
            margin-bottom: 4px;
            font-size: 11px;
        }

        .observations-box {
            width: 100%;
            border: 1px solid #000;
            height: 55px;
            margin-bottom: 15px;
        }

        .disclaimer {
            font-size: 10px;
            margin-bottom: 50px;
        }

        .signatures-container {
            width: 100%;
            margin-top: 40px;
        }

        .signatures-row {
            display: table;
            width: 100%;
            table-layout: fixed;
            margin-bottom: 40px;
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
            padding-top: 5px;
            font-weight: bold;
            font-size: 11px;
        }

        .signature-details {
            font-size: 10.5px;
            margin-top: 2px;
        }

        .vobo-row {
            display: table;
            width: 100%;
            table-layout: fixed;
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
            <td class="logo-area">
                <div class="logo-main">UCO</div>
                <div class="logo-sub">PREPA CONTEMPORÁNEA</div>
                <div class="logo-slogan">Hazlo y actúa sólo para continuar</div>
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
            <td class="value">{{ $empleado->nombre_completo }}</td>
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
