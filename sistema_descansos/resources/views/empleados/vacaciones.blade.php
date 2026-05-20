<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vacaciones | {{ $empleado->nombre_completo }}</title>
    
</head>
<body>
    <main class="page-shell">
        <section class="topbar">
            <div>
                <div class="topbar-title">
                    <span class="page-label">Vacaciones</span>
                    <h1>{{ $empleado->nombre_completo }}</h1>
                </div>
                <p>Administra registros y consulta el consumo de vacaciones de manera clara y moderna.</p>
            </div>

            <a href="{{ route('panel') }}" class="button-link">
                <span class="button-icon">⟵</span>
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
                    <span class="meta-pill">Ingreso: {{ $empleado->fecha_ingreso }}</span>
                    <span class="meta-pill">Antigüedad: {{ $antiguedadAnios }} años</span>
                    <span class="meta-pill">Derecho anual: {{ $diasDerecho }} días</span>
                </div>
            </article>

            <article class="card">
                <strong>Estado actual</strong>
                <div class="meta-row">
                    <span class="meta-pill">Tomados: {{ $diasTomados }}</span>
                    <span class="meta-pill">Restantes: {{ $diasRestantes }}</span>
                    <span class="meta-pill">Año: {{ $anioActual }}</span>
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
                                    <td>{{ $registroPorMes[$numero] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div style="margin-top: 1.5rem;">
                    <a href="{{ route('empleados.vacaciones.pdf', $empleado->id) }}" class="button-secondary" style="width: auto;">porte PDF</a>
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
            diasSeleccionados.textContent = diff;
            previewRestantes.textContent = Math.max(0, diasRestantes - diff);
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
            background-color: #eef4fb;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: radial-gradient(circle at top left, rgba(106, 134, 255, 0.18), transparent 32%),
                        radial-gradient(circle at bottom right, rgba(59, 98, 245, 0.12), transparent 30%),
                        linear-gradient(180deg, #edf4ff 0%, #e5efff 45%, #f3f7ff 100%);
            color: #1f324f;
        }

        .button-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2.5rem;
            height: 2.5rem;
            margin-right: 0.75rem;
            border-radius: 999px;
            background: rgba(59, 98, 245, 0.16);
            color: #3c62f5;
            font-size: 1.25rem;
            font-weight: 700;
            box-shadow: 0 10px 24px rgba(59, 98, 245, 0.12);
        }

        .page-shell {
            width: min(1200px, calc(100% - 2rem));
            margin: 0 auto 2rem;
            padding: 2rem 0;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding: 1.1rem 1.4rem;
            border-radius: 24px;
            background: rgba(255,255,255,0.82);
            box-shadow: 0 24px 60px rgba(31, 50, 79, 0.08);
            backdrop-filter: blur(10px);
        }

        .topbar-title {
            display: flex;
            flex-wrap: wrap;
            align-items: baseline;
            gap: 0.75rem;
            margin-bottom: 0.5rem;
        }

        .page-label {
            display: inline-flex;
            padding: 0.45rem 0.85rem;
            border-radius: 999px;
            text-transform: uppercase;
            letter-spacing: 0.18em;
            font-size: 0.78rem;
            color: #3c62f5;
            background: rgba(59, 98, 245, 0.12);
        }

        .topbar h1 {
            margin: 0;
            font-size: 2rem;
            color: #1f324f;
            letter-spacing: -0.03em;
            line-height: 1.05;
        }

        .topbar p {
            margin: 0.35rem 0 0;
            color: #57627d;
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
            background: rgba(59, 98, 245, 0.12);
            color: #3c62f5;
            font-size: 1rem;
        }

        .button-link,
        .button-primary,
        .button-secondary {
            border: none;
            border-radius: 14px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
            font-weight: 600;
        }

        .button-link {
            padding: 0.9rem 1.3rem;
            color: #3c62f5;
            background: rgba(59, 98, 245, 0.1);
        }

        .button-link:hover {
            transform: translateY(-1px);
            background: rgba(59, 98, 245, 0.16);
        }

        .button-primary {
            width: 100%;
            padding: 1rem 1.1rem;
            background: linear-gradient(135deg, #5567ff 0%, #2744d5 100%);
            color: #ffffff;
        }

        .button-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 16px 30px rgba(58, 87, 151, 0.18);
        }

        .button-secondary {
            background: #f8fbff;
            color: #1f324f;
            border: 1px solid rgba(31, 50, 79, 0.08);
            padding: 0.9rem 1.2rem;
        }

        .button-secondary:hover {
            background: #eef4fb;
        }

        .summary-grid {
            display: grid;
            gap: 1rem;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            margin-bottom: 1.5rem;
        }

        .card {
            background: #ffffff;
            border-radius: 24px;
            padding: 1.6rem;
            box-shadow: 0 18px 40px rgba(31, 50, 79, 0.06);
            border: 1px solid rgba(31, 50, 79, 0.05);
        }

        .card h2,
        .card strong {
            margin: 0 0 0.9rem;
            color: #1f324f;
            font-size: 1.05rem;
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
            grid-template-columns: 1.4fr 1fr;
            margin-top: 1rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #1f324f;
            font-size: 0.95rem;
        }

        input[type="date"],
        select,
        textarea {
            width: 100%;
            padding: 1rem 1rem;
            border-radius: 16px;
            border: 1px solid #d7dee9;
            background: #fbfdff;
            color: #20304a;
            font-size: 0.97rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #6a86ff;
            box-shadow: 0 0 0 4px rgba(106, 134, 255, 0.12);
        }

        .status {
            border-radius: 18px;
            padding: 1rem 1.2rem;
            margin-bottom: 1.5rem;
            font-size: 0.98rem;
            line-height: 1.6;
        }

        .status.success {
            background: #e4f8f2;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .status.error {
            background: #fff1f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 320px;
        }

        th,
        td {
            padding: 1rem 0.75rem;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
            color: #3f4d67;
        }

        th {
            background: #f8fbff;
            color: #1f324f;
            letter-spacing: 0.01em;
            font-weight: 600;
        }

        .meta-row {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem 1.5rem;
            margin-top: 0.75rem;
        }

        .meta-pill {
            padding: 0.8rem 1rem;
            border-radius: 999px;
            background: #f5f8ff;
            color: #3f4d67;
            font-size: 0.95rem;
        }

        @media (max-width: 860px) {
            .summary-grid,
            .card-columns {
                grid-template-columns: 1fr;
            }

            .topbar {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
