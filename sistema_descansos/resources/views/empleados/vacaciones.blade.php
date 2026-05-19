<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vacaciones | {{ $empleado->nombre_completo }}</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f3f4f6; margin: 0; }
        .navbar { background-color: #1f2937; color: white; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; }
        .navbar a { color: #f87171; text-decoration: none; font-weight: bold; }
        .container { max-width: 1100px; margin: 30px auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        h1 { color: #374151; font-size: 24px; border-bottom: 2px solid #e5e7eb; padding-bottom: 10px; margin-bottom: 20px; }
        .grid { display: grid; gap: 16px; grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .card { background: #f9fafb; padding: 18px; border-radius: 8px; border: 1px solid #e5e7eb; }
        .card strong { display:block; margin-bottom: 8px; color:#374151; }
        .status { margin-bottom: 20px; padding: 12px 16px; border-radius: 8px; }
        .success { background:#d1fae5; color:#065f46; }
        .error { background:#fee2e2; color:#991b1b; }
        form { background: #ffffff; padding: 18px; border-radius: 8px; border: 1px solid #e5e7eb; }
        label { display:block; margin-bottom: 8px; font-weight:600; color:#374151; }
        select,input { width:100%; padding:10px 12px; margin-bottom:12px; border-radius:6px; border:1px solid #d1d5db; }
        button { background-color:#2563eb; color:white; border:none; padding:10px 16px; border-radius:6px; cursor:pointer; }
        button:hover { background-color:#1d4ed8; }
        .btn-secondary { background:#6b7280; }
        table { width:100%; border-collapse: collapse; margin-top: 16px; }
        th, td { text-align:left; padding:12px; border-bottom:1px solid #e5e7eb; }
        th { background:#f3f4f6; color:#374151; }
        .actions { margin-top: 20px; display:flex; justify-content: space-between; gap:12px; flex-wrap: wrap; }
    </style>
</head>
<body>
    <div class="navbar">
        <div>Control de Descansos UPQ</div>
        <div>
            Hola, {{ session('nombre') }} | <a href="{{ route('logout') }}">Cerrar Sesión</a>
        </div>
    </div>

    <div class="container">
        <div class="actions">
            <h1>Vacaciones de {{ $empleado->nombre_completo }}</h1>
            <a class="btn-secondary" href="{{ route('panel') }}">Volver al Panel</a>
        </div>

        @if ($errors->any())
            <div class="status error">
                <ul style="margin:0; padding-left:18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="status success">{{ session('success') }}</div>
        @endif

        <div class="grid">
            <div class="card">
                <strong>Información del empleado</strong>
                <p><strong>Fecha de ingreso:</strong> {{ $empleado->fecha_ingreso }}</p>
                <p><strong>Años trabajados:</strong> {{ $antiguedadAnios }}</p>
                <p><strong>Días de derecho este año:</strong> {{ $diasDerecho }}</p>
            </div>
            <div class="card">
                <strong>Estado de vacaciones</strong>
                <p><strong>Días tomados:</strong> {{ $diasTomados }}</p>
                <p><strong>Días restantes:</strong> <span id="dias-restantes">{{ $diasRestantes }}</span></p>
                <p><strong>Año:</strong> {{ $anioActual }}</p>
            </div>
        </div>

        <div style="margin-top: 30px; display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <form action="{{ route('empleados.vacaciones.guardar', $empleado->id) }}" method="POST">
                @csrf
                <h2>Registrar días de vacaciones</h2>
                <label for="fecha_inicio">Fecha de inicio</label>
                <input type="date" id="fecha_inicio" name="fecha_inicio" value="{{ old('fecha_inicio') }}" />

                <label for="fecha_fin">Fecha de fin</label>
                <input type="date" id="fecha_fin" name="fecha_fin" value="{{ old('fecha_fin') }}" />

                <p style="margin-top: 0; color: #4b5563;">Selecciona el rango de vacaciones. Se calcularán los días automáticamente.</p>
                <p><strong>Días seleccionados:</strong> <span id="dias-seleccionados">0</span></p>
                <p><strong>Proyección de días restantes:</strong> <span id="preview-restantes">{{ max(0, $diasRestantes - 1) }}</span></p>
                <button type="submit">Guardar registro</button>
            </form>

            <div class="card">
                <h2>Consumo mensual</h2>
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
                <div class="actions" style="margin-top: 18px; justify-content:flex-start;">
                    <a href="{{ route('empleados.vacaciones.pdf', $empleado->id) }}" class="btn-secondary" style="background:#10b981; color:white; padding:10px 14px; text-decoration:none;">Generar reporte PDF</a>
                </div>
            </div>
        </div>
    </div>

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
