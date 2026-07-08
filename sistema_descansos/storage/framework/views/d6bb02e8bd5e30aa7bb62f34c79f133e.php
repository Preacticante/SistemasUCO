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
            line-height: 1.2;
            background-color: #fff;
            padding: 0;
        }

        .container {
            width: 210mm;
            height: 297mm;
            margin: 0 auto;
            padding: 20px;
            background: white;
        }

        /* ========== HEADER ========== */
        .header {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }

        .header-cell {
            display: table-cell;
            vertical-align: top;
        }

        .logo-cell {
            width: 100px;
            text-align: left;
        }

        .logo-cell img {
            width: 90px;
            height: auto;
        }

        .title-cell {
            width: 60%;
            text-align: center;
            padding: 0 20px;
            vertical-align: middle;
        }

        .title-cell h1 {
            font-size: 13px;
            font-weight: bold;
            line-height: 1.2;
            margin: 0;
        }

        .date-cell {
            width: 140px;
            text-align: right;
        }

        .date-label {
            font-size: 10px;
            margin-bottom: 2px;
        }

        .date-value {
            border-bottom: 1px solid #000;
            padding: 2px 4px;
            font-weight: bold;
            font-size: 10px;
            text-align: center;
        }

        /* ========== INTRO TEXT ========== */
        .intro-text {
            font-size: 10px;
            text-align: justify;
            margin: 10px 0;
            line-height: 1.3;
        }

        /* ========== INFO TABLE ========== */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            border: 1px solid #000;
        }

        .info-table tr {
            border: 1px solid #000;
        }

        .info-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            font-size: 10px;
        }

        .info-table .label {
            font-weight: bold;
            width: 12%;
            background-color: #f0f0f0;
        }

        .info-table .value {
            width: 88%;
        }

        /* ========== BALANCES ROW ========== */
        .balance-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 8px 0;
            font-size: 10px;
            gap: 10px;
            flex-wrap: wrap;
        }

        .balance-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .balance-label {
            font-weight: bold;
        }

        .balance-box {
            border: 1px solid #000;
            padding: 4px 8px;
            font-weight: bold;
            min-width: 30px;
            text-align: center;
            font-size: 11px;
        }

        /* ========== TABLE TITLE ========== */
        .table-title {
            font-weight: bold;
            font-size: 10px;
            margin: 10px 0 5px 0;
            text-align: center;
        }

        /* ========== VACATION TABLE ========== */
        .vacation-table {
            width: 100%;
            border-collapse: collapse;
            margin: 5px 0 8px 0;
            border: 1px solid #000;
        }

        .vacation-table th {
            border: 1px solid #000;
            padding: 5px 2px;
            font-weight: bold;
            font-size: 9px;
            text-align: center;
            background-color: #fff;
        }

        .vacation-table td {
            border: 1px solid #000;
            padding: 5px 2px;
            font-size: 9px;
            text-align: center;
            height: 20px;
        }

        /* ========== OBSERVATIONS ========== */
        .observations-title {
            font-weight: bold;
            font-size: 10px;
            margin: 8px 0 5px 0;
        }

        .observations-box {
            border: 1px solid #000;
            padding: 8px;
            min-height: 45px;
            font-size: 10px;
            text-align: left;
            line-height: 1.3;
        }

        /* ========== DISCLAIMER ========== */
        .disclaimer {
            font-size: 9px;
            margin-top: 6px;
            text-align: justify;
            line-height: 1.2;
        }

        /* ========== SIGNATURES ========== */
        .signatures-section {
            margin-top: 40px;
        }

        .signatures-row {
            display: table;
            width: 100%;
            table-layout: fixed;
            margin-bottom: 30px;
        }

        .signature-col {
            display: table-cell;
            width: 33.33%;
            text-align: center;
            vertical-align: top;
        }

        .signature-line {
            border-top: 1px solid #000;
            height: 45px;
            margin-bottom: 4px;
        }

        .signature-title {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.2;
            margin-bottom: 3px;
        }

        .signature-name {
            font-size: 9px;
            margin-bottom: 1px;
        }

        .signature-position {
            font-size: 8px;
            color: #666;
        }
    </style>
</head>
<body>

<div class="container">
    
    <!-- HEADER -->
    <div class="header">
        <div class="header-cell logo-cell">
            <?php
                $path = public_path('img/logo_uco.png');
                if (file_exists($path)) {
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                } else {
                    $base64 = '';
                }
            ?>
            <?php if($base64): ?>
                <img src="<?php echo e($base64); ?>" alt="UCO">
            <?php endif; ?>
        </div>
        <div class="header-cell title-cell">
            <h1>CONSTANCIA DE PERIODO<br>VACACIONAL</h1>
        </div>
        <div class="header-cell date-cell">
            <div class="date-label">Fecha: _______________</div>
            <div class="date-value"><?php echo e($fecha->format('d-M-y')); ?></div>
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
            <td class="value"><?php echo e($empleado->nombre); ?> <?php echo e($empleado->apellido_paterno); ?> <?php echo e($empleado->apellido_materno); ?></td>
        </tr>
        <tr>
            <td class="label">Puesto:</td>
            <td class="value"><?php echo e($puesto?->nombre ?? 'No asignado'); ?></td>
        </tr>
        <tr>
            <td class="label">Área:</td>
            <td class="value"><?php echo e($empleado->area ?? 'Mantenimiento y Seguridad'); ?></td>
        </tr>
    </table>

    <!-- BALANCE 1 -->
    <div class="balance-row">
        <div class="balance-item">
            <span class="balance-label">Días pendientes por disfrutar:</span>
            <span class="balance-box"><?php echo e($diasRestantes); ?></span>
            <span>días del Período Vacacional</span>
            <span class="balance-box"><?php echo e($anioActual); ?></span>
        </div>
        <div class="balance-item">
            <span class="balance-label">Días con derecho:</span>
            <span class="balance-box"><?php echo e($diasDerecho); ?></span>
        </div>
    </div>

    <!-- TABLE TITLE -->
    <div class="table-title">Último período vacacional disfrutado:</div>

    <!-- VACATION TABLE -->
    <?php
        $ultimoPeriodo = $periodoSeleccionado ?? ($periodosVacacionales instanceof \Illuminate\Support\Collection 
            ? $periodosVacacionales->first() 
            : (is_array($periodosVacacionales) ? reset($periodosVacacionales) : null));
    ?>

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
            <?php if($ultimoPeriodo && $ultimoPeriodo->fecha_inicio && $ultimoPeriodo->fecha_fin): ?>
                <tr>
                    <td><?php echo e($ultimoPeriodo->anio_calendario ?? $anioActual); ?></td>
                    <td><?php echo e($ultimoPeriodo->dias ?? 0); ?></td>
                    <td><?php echo e(\Carbon\Carbon::parse($ultimoPeriodo->fecha_inicio)->format('d')); ?></td>
                    <td><?php echo e(\Carbon\Carbon::parse($ultimoPeriodo->fecha_inicio)->format('m')); ?></td>
                    <td><?php echo e(\Carbon\Carbon::parse($ultimoPeriodo->fecha_inicio)->format('Y')); ?></td>
                    <td><?php echo e(\Carbon\Carbon::parse($ultimoPeriodo->fecha_fin)->format('d')); ?></td>
                    <td><?php echo e(\Carbon\Carbon::parse($ultimoPeriodo->fecha_fin)->format('m')); ?></td>
                    <td><?php echo e(\Carbon\Carbon::parse($ultimoPeriodo->fecha_fin)->format('Y')); ?></td>
                    <td><?php echo e(\Carbon\Carbon::parse($ultimoPeriodo->fecha_regreso ?? $ultimoPeriodo->fecha_fin->addDay())->format('d')); ?></td>
                    <td><?php echo e(\Carbon\Carbon::parse($ultimoPeriodo->fecha_regreso ?? $ultimoPeriodo->fecha_fin->addDay())->format('m')); ?></td>
                    <td><?php echo e(\Carbon\Carbon::parse($ultimoPeriodo->fecha_regreso ?? $ultimoPeriodo->fecha_fin->addDay())->format('Y')); ?></td>
                </tr>
            <?php else: ?>
                <tr>
                    <td><?php echo e($anioActual); ?></td>
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
            <?php endif; ?>
        </tbody>
    </table>

    <!-- BALANCE 2 -->
    <div class="balance-row">
        <div class="balance-item">
            <span class="balance-label">Días restantes por disfrutar:</span>
            <span class="balance-box"><?php echo e($diasRestantes); ?></span>
            <span>días del Período Vacacional</span>
            <span class="balance-box"><?php echo e($anioActual); ?></span>
        </div>
    </div>

    <!-- OBSERVATIONS -->
    <div class="observations-title">Observaciones:</div>
    <div class="observations-box">
        <?php echo e($ultimoPeriodo && $ultimoPeriodo->observaciones ? $ultimoPeriodo->observaciones : 'Ninguna.'); ?>

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
                <div class="signature-name"><?php echo e($empleado->nombre); ?> <?php echo e($empleado->apellido_paterno); ?> <?php echo e($empleado->apellido_materno); ?></div>
                <div class="signature-position"><?php echo e($puesto?->nombre ?? 'Puesto'); ?></div>
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
</html><?php /**PATH /var/www/html/resources/views/empleados/pdf.blade.php ENDPATH**/ ?>