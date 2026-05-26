<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vacaciones | {{ $empleado->nombre }} {{ $empleado->apellido_paterno }} {{ $empleado->apellido_materno }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <main class="page-shell">

        @php
            $path = public_path('img/logo_uco.png');
            $base64 = '';
            if (file_exists($path)) {
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
        @endphp

        @if($base64)
            <div class="logo-outer-container">
                <img src="{{ $base64 }}" alt="Logo UCO" class="logo-outside">
            </div>
        @endif
        
        <section class="topbar">
            <div>
                <div class="topbar-title">
                    <span class="page-label">Vacaciones</span>
                    <h1>{{ $empleado->nombre }} {{ $empleado->apellido_paterno }} {{ $empleado->apellido_materno }}</h1>
                </div>
                <p>Administra registros y consulta el consumo de vacaciones de manera clara.</p>
            </div>

            <a href="{{ route('panel') }}" class="button-link">
                <span class="button-icon"></span>
                Volver al inicio
            </a>
        </section>

        @if ($errors->any())
            <div class="status error">
                <ul style="margin: 0; padding-left: 1.2rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="status success">{{ session('success') }}</div>
        @endif

        <section class="summary-grid">
            <article class="card">
                <strong>Información del empleado</strong>
                <div class="meta-row">
                    <span class="meta-pill">Ingreso: <strong>{{ $empleado->fecha_ingreso }}</strong></span>
                    <span class="meta-pill">Antigüedad: <strong>{{ $antiguedadAnios }} años</strong></span>
                    <span class="meta-pill">Derecho anual: <strong>{{ $diasDerecho }} días</strong></span>
                </div>
            </article>

            <article class="card">
                <strong>Estado actual</strong>
                <div class="meta-row">
                    <span class="meta-pill">Tomados: <strong style="color: #ef4444;">{{ $diasTomados }}</strong></span>
                    <span class="meta-pill">Restantes: <strong style="color: #124416;">{{ $diasRestantes }}</strong></span>
                    <span class="meta-pill">Año: <strong>{{ $anioActual }}</strong></span>
                </div>
            </article>
        </section>

        <section class="card-columns">
            <article class="card">
                <h2>Registrar vacaciones</h2>
                <form action="{{ route('empleados.vacaciones.guardar', $empleado->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="fecha_inicio">Fecha de inicio</label>
                        <input type="date" id="fecha_inicio" name="fecha_inicio" value="{{ old('fecha_inicio') }}" />
                    </div>

                    <div class="form-group">
                        <label for="fecha_fin">Fecha de fin</label>
                        <input type="date" id="fecha_fin" name="fecha_fin" value="{{ old('fecha_fin') }}" />
                    </div>

                    <div class="form-group" style="margin-bottom: 0.6rem; color: #57627d; font-size: 0.95rem;">
                        Ajusta el rango y revisa la proyección antes de guardar.
                    </div>

                    <div class="meta-row" style="margin-bottom: 1.25rem;">
                        <span class="meta-pill">Días seleccionados: <strong id="dias-seleccionados">0</strong></span>
                        <span class="meta-pill">Restantes estimados: <strong id="preview-restantes">{{ max(0, $diasRestantes - 1) }}</strong></span>
                    </div>

                    <button type="submit" class="button-primary">Guardar registro</button>
                </form>
            </article>

            <article class="card">
                <h2>Consumo mensual</h2>
                <div class="table-wrapper">
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
                                    <td><strong>{{ $registroPorMes[$numero] }}</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div style="margin-top: 1.5rem;">
                    <a href="{{ route('empleados.vacaciones.pdf', $empleado->id) }}" target="_blank" rel="noopener noreferrer" class="button-secondary" style="width: auto;">Reporte PDF</a>
                </div>
            </article>
        </section>
    </main>

    <script>
        const diasRestantes = {{ $diasRestantes }};
        const fechaInicio = document.getElementById('fecha_inicio');
        const fechaFin = document.getElementById('fecha_fin');
        const diasSeleccionados = document.getElementById('dias-seleccionados');
        const previewRestantes = document.getElementById('preview-restantes');

        function calcularDias() {
            const inicio = fechaInicio.value ? new Date(fechaInicio.value) : null;
            const fin = fechaFin.value ? new Date(fechaFin.value) : null;

            if (!inicio || !fin || fin < inicio) {
                diasSeleccionados.textContent = 0;
                previewRestantes.textContent = diasRestantes;
                return;
            }

            const diff = Math.floor((fin - inicio) / (1000 * 60 * 60 * 24)) + 1;
            const diffDays = isNaN(diff) ? 0 : diff;
            diasSeleccionados.textContent = diffDays;
            previewRestantes.textContent = Math.max(0, diasRestantes - diffDays);
        }

        fechaInicio.addEventListener('change', () => {
            if (fechaFin.value && fechaFin.value < fechaInicio.value) {
                fechaFin.value = fechaInicio.value;
            }
            calcularDias();
        });

        fechaFin.addEventListener('change', calcularDias);
        calcularDias();
    </script>
</body>
</html>

<style>
    :root {
        font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        color: #1f324f;
        background-color: #f1f5f9;
    }

    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        min-height: 100vh;
        background: radial-gradient(circle at top left, rgba(52, 12, 81, 0.08), transparent 35%),
                    radial-gradient(circle at bottom right, rgba(170, 127, 49, 0.05), transparent 30%),
                    linear-gradient(180deg, #f1f5f9 0%, #edf2f7 100%);
        color: #1e293b;
    }

    .page-shell {
        width: min(1200px, calc(100% - 2rem));
        margin: 0 auto 2rem;
        padding: 2rem 0;
    }

    /* --- Encabezado Superior (Estilo Redondeado de la marca) --- */
    .topbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding: 1.5rem 2rem;
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.85);
        box-shadow: 0 10px 30px rgba(52, 12, 81, 0.03);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.8);
    }

    .topbar-title {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 1rem;
        margin-bottom: 0.5rem;
    }

    /* Label - MORADO INSTITUCIONAL */
    .page-label {
        display: inline-flex;
        padding: 0.45rem 0.95rem;
        border-radius: 999px;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        font-size: 0.75rem;
        font-weight: 700;
        color: white;
        background: #340C51; 
    }

    .topbar h1 {
        margin: 0;
        font-size: 1.8rem;
        color: #340C51; /* MORADO INSTITUCIONAL */
        letter-spacing: -0.02em;
        font-weight: 700;
    }

    .topbar p {
        margin: 0.4rem 0 0;
        color: #64748b;
        font-size: 0.95rem;
    }

    .button-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2rem;
        height: 2rem;
        margin-right: 0.6rem;
        border-radius: 999px;
        background: rgba(52, 12, 81, 0.1);
        color: #340C51;
        font-size: 1rem;
    }

    .button-link,
    .button-primary,
    .button-secondary {
        border: none;
        border-radius: 12px;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
        font-weight: 600;
    }

    /* Botón Volver - Acento Morado */
    .button-link {
        padding: 0.8rem 1.4rem;
        color: #340C51;
        background: rgba(52, 12, 81, 0.08);
    }

    .button-link:hover {
        transform: translateY(-1px);
        background: #340C51;
        color: white;
    }

    /* Botón Guardar - VERDE INSTITUCIONAL */
    .button-primary {
        width: 100%;
        padding: 1rem;
        background: #124416;
        color: #ffffff;
        font-size: 1rem;
        box-shadow: 0 4px 12px rgba(18, 68, 22, 0.15);
    }

    .button-primary:hover {
        transform: translateY(-1px);
        background-color: #0d3310;
        box-shadow: 0 6px 15px rgba(18, 68, 22, 0.25);
    }

    /* Botón Reporte - DORADO INSTITUCIONAL */
    .button-secondary {
        background: rgba(170, 127, 49, 0.1);
        color: #AA7F31;
        border: 1px solid rgba(170, 127, 49, 0.2);
        padding: 0.8rem 1.4rem;
    }

    .button-secondary:hover {
        background: #AA7F31;
        color: white;
    }

    .summary-grid {
        display: grid;
        gap: 1.5rem;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        margin-bottom: 1.5rem;
    }

    /* Tarjetas Modificadas Redondeadas */
    .card {
        background: #ffffff;
        border-radius: 50px;
        padding: 1.6rem;
        box-shadow: 0 8px 25px rgba(0,0,0,0.02);
        border: 1px solid rgba(0, 0, 0, 0.03);
        position: relative;
    }

    .card h2,
    .card strong {
        display: block;
        margin: 0 0 1rem;
        color: #340C51; /* MORADO INSTITUCIONAL */
        font-size: 1.1rem;
        font-weight: 700;
    }

    .card p,
    .card li {
        margin: 0.75rem 0;
        color: #55627d;
        line-height: 1.65;
    }

    .card-columns {
        display: grid;
        gap: 1.5rem;
        grid-template-columns: 1.3fr 1fr;
        margin-top: 1rem;
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    label {
        display: block;
        margin-bottom: 0.5rem;
        color: #475569;
        font-size: 0.9rem;
        font-weight: 500;
    }

    input[type="date"] {
        width: 100%;
        padding: 0.85rem 1rem;
        border-radius: 12px;
        border: 1px solid #cbd5e1;
        background: #f8fafc;
        color: #1e293b;
        font-size: 0.95rem;
        font-family: inherit;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        outline: none;
    }

    input[type="date"]:focus {
        border-color: #AA7F31; /* DORADO INSTITUCIONAL */
        box-shadow: 0 0 0 3px rgba(170, 127, 49, 0.15);
    }

    .status {
        border-radius: 14px;
        padding: 1rem 1.2rem;
        margin-bottom: 1.5rem;
        font-size: 0.98rem;
        font-weight: 500;
    }

    .status.success {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    .status.error {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 280px;
    }

    th, td {
        padding: 1rem 0.75rem;
        text-align: left;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.95rem;
    }

    th {
        background: #f8fafc;
        color: #340C51; /* MORADO INSTITUCIONAL */
        font-weight: 600;
    }

    td strong {
        color: #340C51;
    }

    .meta-row {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-top: 0.75rem;
    }

    /* Píldoras Informativas */
    .meta-pill {
        padding: 0.7rem 1.1rem;
        border-radius: 50px;
        background: #f8fafc;
        color: #475569;
        font-size: 0.9rem;
        border: 1px solid #e2e8f0;
    }
    
    .meta-pill strong {
        color: #340C51;
        display: inline;
    }

    .logo-outer-container {
        width: 100%;
        display: flex;
        justify-content: flex-start;
        padding-left: 0.5rem;
        margin-bottom: 1rem;
    }

    .logo-outside {
        height: 85px;
        width: auto;
        object-fit: contain;
    }

    @media (max-width: 860px) {
        .summary-grid,
        .card-columns {
            grid-template-columns: 1fr;
        }

        .topbar {
            flex-direction: column;
            align-items: flex-start;
            padding: 1.5rem;
        }

        .logo-outer-container {
            justify-content: center;
            padding-left: 0;
        }
        
        .logo-outside {
            height: 65px;
        }
    }
</style>