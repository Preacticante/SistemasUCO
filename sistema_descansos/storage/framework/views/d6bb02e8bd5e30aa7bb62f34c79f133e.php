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
            line-height: 1.4; /* Aumentado para dar más aire al texto */
            background-color: #fff;
            padding: 0;
        }

        .container {
            width: calc(100% - 16px);
            max-width: 190mm;
            margin: 0 auto;
            padding: 24px 15px 30px; /* Más márgenes internos generales */
            background: white;
        }

        /* ========== HEADER ========== */
        .header {
            display: table;
            width: 100%;
            margin-bottom: 20px; /* Más separación abajo del encabezado */
        }

        .header-cell {
            display: table-cell;
            vertical-align: middle; /* Alineación centralizada */
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
        }

        .title-cell h1 {
            font-size: 13px;
            font-weight: bold;
            line-height: 1.2;
            margin: 0;
        }

        .date-cell {
            width: 150px;
            text-align: right;
        }

        /* Nueva estructura para fijar la fecha sobre su línea */
        .date-container {
            display: block;
            text-align: right;
            white-space: nowrap;
        }

        .date-label {
            font-size: 10px;
            font-weight: normal;
            display: inline-block;
            margin-right: 4px;
        }

        .date-underline-value {
            border-bottom: 1px solid #000;
            padding: 0 12px 2px 12px;
            font-weight: bold;
            font-size: 10px;
            text-align: center;
            display: inline-block;
            min-width: 95px; /* Define el ancho exacto de la línea de la fecha */
        }

        /* ========== INTRO TEXT ========== */
        .intro-text {
            font-size: 10px;
            text-align: justify;
            margin: 25px 0; /* Más espacio arriba y abajo */
            line-height: 1.4;
        }

        /* ========== INFO TABLE ========== */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0; /* Más espacio que la separa de las demás secciones */
            border: 1px solid #000;
        }

        .info-table tr {
            border: 1px solid #000;
        }

        .info-table td {
            border: 1px solid #000;
            padding: 8px 12px; /* Celdas más altas y cómodas */
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
            margin: 18px 0; /* Más espacio entre renglones de balances */
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
            margin: 30px 0 12px 0; /* Separación amplia antes del título de la tabla */
            text-align: center;
        }

        /* ========== VACATION TABLE ========== */
        .vacation-table {
            width: 100%;
            border-collapse: collapse;
            margin: 12px 0 30px 0; /* Espacio extendido en la parte inferior */
            border: 1px solid #000;
        }

        .vacation-table th,
        .vacation-table td {
            border: 1px solid #000;
            padding: 8px 6px; /* Números con mejor espacio vertical */
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
            height: 25px;
        }

        /* ========== OBSERVATIONS ========== */
        .observations-title {
            font-weight: bold;
            font-size: 10px;
            margin: 25px 0 8px 0;
        }

        .observations-box {
            border: 1px solid #000;
            padding: 12px;
            min-height: 65px; /* Caja ligeramente más alta */
            font-size: 10px;
            text-align: left;
            line-height: 1.4;
            background-color: #fff;
        }

        /* ========== DISCLAIMER ========== */
        .disclaimer {
            font-size: 9px;
            margin-top: 20px; /* Más despegado del cuadro de observaciones */
            text-align: justify;
            line-height: 1.2;
        }

        /* ========== SIGNATURES ========== */
        .signatures-section {
            margin-top: 85px; /* Empuja las firmas notablemente hacia abajo */
            width: 100%;
        }

        .signatures-table {
            width: 100%;
            border-collapse: collapse;
            border: none !important;
        }

        .signatures-table td {
            border: none !important;
            padding: 0;
            vertical-align: top;
            text-align: center;
        }

        .signature-line {
            width: 170px; /* Un poco más largas para balancear el diseño */
            border-top: 1px solid #000;
            margin: 0 auto 12px auto; /* Más separación entre la línea y el cargo */
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
            <div class="date-container">
                <span class="date-label">Fecha:</span>
                <span class="date-underline-value"><?php echo e($fecha->format('d-M-y')); ?></span>
            </div>
        </div>
    </div>

    <div class="intro-text">
        De conformidad con el Artículo 76 de la Ley Federal del Trabajo, se extiende la presente Constancia de Período Vacacional que usted disfrutará.
    </div>

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

    <div class="balance-row">
        <div class="balance-item">
            <span class="balance-label">Días pendientes por disfrutar:</span>
            <span class="balance-box"><?php echo e($diasRestantes); ?></span>
            <span>días del Período Vacacional</span>
            <span class="balance-box">2025</span>
        </div>
        <div class="balance-item">
            <span class="balance-label">Días con derecho:</span>
            <span class="balance-box"><?php echo e($diasDerecho); ?></span>
        </div>
    </div>

    <div class="table-title">Último período vacacional disfrutado:</div>

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
                    <td><?php echo e($periodoVisual ?? $periodoAnio); ?></td>
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

    <div class="balance-row">
        <div class="balance-item">
            <span class="balance-label">Días restantes por disfrutar:</span>
            <span class="balance-box"><?php echo e($diasRestantes); ?></span>
            <span>días del Período Vacacional</span>
            <span class="balance-box">2025</span>
        </div>
    </div>

    <?php if(isset($ajustesUsados) && $ajustesUsados->count() > 0): ?>
        <?php $ajustesUsados = $ajustesUsados; ?>
        <?php $__currentLoopData = $ajustesUsados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ajuste): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="balance-row">
                <div class="balance-item">
                    <span class="balance-label">Días del periodo <?php echo e($ajuste->anio); ?>:</span>
                    <span class="balance-box"><?php echo e($ajuste->restante); ?></span>
                    <span>días del Período Vacacional</span>
                    <span class="balance-box"><?php echo e($ajuste->anio); ?></span>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>

    <div class="observations-title">Observaciones:</div>
    <div class="observations-box">
        <?php echo e($ultimoPeriodo && $ultimoPeriodo->observaciones ? $ultimoPeriodo->observaciones : 'Ninguna.'); ?>

    </div>

    <div class="disclaimer">
        * Quedando de conformidad que no se me adeuda ningún día de vacaciones adicionales a los indicados.
    </div>

    <div class="signatures-section">
        
        <table class="signatures-table" style="margin-bottom: 55px;">
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

</div>

</body>
</html><?php /**PATH /var/www/html/resources/views/empleados/pdf.blade.php ENDPATH**/ ?>