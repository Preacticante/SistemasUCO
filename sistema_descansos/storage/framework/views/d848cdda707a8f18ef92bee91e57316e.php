<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Vacaciones</title>
</head>
<body>

<div class="container">

    <table class="header-table">
        <tr>
            <td class="logo-area" style="text-align: left;">
                <?php
                    $path = public_path('img/logo_uco.png');
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                ?>
                <img src="<?php echo e($base64); ?>" alt="UCO PREPA CONTEMPORÁNEA" style="height: 160px; margin-bottom: 2px;">
            </td>
            <td class="title-area">
                HISTORIAL DE PERÍODOS VACACIONALES
            </td>
            <td class="date-area">
                Fecha: <span class="date-box"><?php echo e($fecha->format('d-M-y')); ?></span>
                <div class="date-format">dd/mm/aa</div>
            </td>
        </tr>
    </table>

    <div class="intro-text">
        A continuación se presenta el historial de todas las solicitudes de vacaciones registradas durante el año <?php echo e($anioActual); ?>.
    </div>

    <table class="info-table">
        <tr>
            <td class="label">Nombre:</td>
            <td class="value"><?php echo e($empleado->nombre); ?> <?php echo e($empleado->apellido_paterno); ?> <?php echo e($empleado->apellido_materno); ?></td>
        </tr>
        <tr>
            <td class="label">Puesto:</td>
            <td class="value"><?php echo e($puesto?->nombre ?? 'Ayudante General'); ?></td>
        </tr>
        <tr>
            <td class="label">Área:</td>
            <td class="value"><?php echo e($empleado->area ?? 'Mantenimiento y Seguridad'); ?></td>
        </tr>
    </table>

    <div class="historial-title">Historial de solicitudes</div>

    <table class="vacation-table" style="margin-bottom: 16px;">
        <thead>
            <tr>
                <th style="width: 14%;">Inicio</th>
                <th style="width: 14%;">Fin</th>
                <th style="width: 8%;">Días</th>
                <th style="width: 64%;">Observaciones</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $periodosVacacionales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $periodo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($periodo->fecha_inicio ? \Carbon\Carbon::parse($periodo->fecha_inicio)->format('d/m/Y') : ''); ?></td>
                    <td><?php echo e($periodo->fecha_fin ? \Carbon\Carbon::parse($periodo->fecha_fin)->format('d/m/Y') : ''); ?></td>
                    <td style="text-align: center; font-weight: bold;"><?php echo e($periodo->dias); ?></td>
                    <td><?php echo e($periodo->observaciones ?: 'Ninguna.'); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="4" style="text-align: center; padding: 18px 0;">No se registraron solicitudes de vacaciones para este año.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <table class="balances-table" style="margin-bottom: 20px;">
        <tr>
            <td style="width: 28%; font-weight: bold;">Total de días solicitados:</td>
            <td class="cell-inline-border" style="width: 8%;"><?php echo e($totalDiasSolicitados); ?></td>
            <td style="width: 64%;"></td>
        </tr>
    </table>

    <div class="observations-title">Notas</div>
    <div class="observations-box">
        El historial incluye todas las solicitudes grabadas en el sistema durante el año <?php echo e($anioActual); ?>.
    </div>

    <div class="disclaimer">
        * Este documento refleja el historial de solicitudes vacacionales y no sustituye la constancia oficial de periodo vacacional.
    </div>



<style>
    body {
        margin: 0;
        font-family: Arial, Helvetica, sans-serif;
        color: #1f2937;
        background-color: #fff;
    }
    .container {
        width: 100%;
        padding: 20px;
    }
    .header-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 18px;
    }
    .header-table td {
        vertical-align: top;
    }
    .title-area {
        text-align: center;
        font-size: 24px;
        font-weight: 800;
        letter-spacing: 1px;
        text-transform: uppercase;
        padding-top: 18px;
    }
    .date-area {
        text-align: right;
        font-size: 12px;
    }
    .date-box {
        display: inline-block;
        padding: 6px 10px;
        background: #f8fafc;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        margin-top: 4px;
        font-weight: 700;
    }
    .date-format {
        font-size: 10px;
        color: #64748b;
        margin-top: 2px;
    }
    .intro-text {
        margin-bottom: 16px;
        font-size: 12px;
        line-height: 1.6;
    }
    .info-table,
    .balances-table,
    .vacation-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 12px;
    }
    .info-table td,
    .balances-table td,
    .vacation-table th,
    .vacation-table td {
        border: 1px solid #cbd5e1;
        padding: 10px 12px;
        font-size: 11px;
    }
    .info-table .label {
        width: 22%;
        font-weight: 700;
        background: #f8fafc;
    }
    .info-table .value {
        width: 78%;
    }
    .vacation-table th {
        background: #124416;
        color: #fff;
        font-weight: 700;
        text-align: left;
    }
    .vacation-table td {
        background: #fff;
    }
    .historial-title {
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 8px;
    }
    .observations-title {
        font-size: 12px;
        font-weight: 700;
        margin-top: 18px;
        margin-bottom: 6px;
    }
    .observations-box {
        border: 1px solid #cbd5e1;
        background: #f8fafc;
        border-radius: 10px;
        padding: 12px;
        font-size: 11px;
        line-height: 1.6;
    }
    .disclaimer {
        margin-top: 18px;
        font-size: 10px;
        color: #475569;
    }
    .spacer-firmas {
        height: 50px;
    }
    .signatures-container {
        width: 100%;
        display: table;
        table-layout: fixed;
        margin-top: 10px;
    }
    .signatures-row {
        display: table-row;
    }
    .signature-col {
        display: table-cell;
        width: 45%;
        text-align: center;
        vertical-align: bottom;
    }
    .signature-space {
        display: table-cell;
        width: 10%;
    }
    .signature-line {
        border-top: 1px solid #000;
        padding-top: 6px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
    }
    .signature-details {
        font-size: 9px;
        margin-top: 4px;
    }
    .cell-inline-border {
        border: 1px solid #cbd5e1;
        text-align: center;
        font-weight: 700;
    }
</style>

</body>
</html>
<?php /**PATH /var/www/html/resources/views/empleados/pdf_historial.blade.php ENDPATH**/ ?>