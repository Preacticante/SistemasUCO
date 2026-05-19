<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Principal | Sistema de Descansos</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f3f4f6; margin: 0; }
        .navbar { background-color: #1f2937; color: white; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; }
        .navbar a { color: #f87171; text-decoration: none; font-weight: bold; }
        .container { max-width: 1000px; margin: 30px auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        h1 { color: #374151; font-size: 24px; border-bottom: 2px solid #e5e7eb; padding-bottom: 10px; margin-bottom: 20px;}
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { text-align: left; padding: 12px; border-bottom: 1px solid #e5e7eb; }
        th { background-color: #f9fafb; color: #4b5563; }
        .btn-calcular { background-color: #10b981; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 14px; }
        .btn-calcular:hover { background-color: #059669; }
    </style>
</head>
<body>

    <div class="navbar">
        <div>Control de Descansos UPQ</div>
        <div>
            Hola, {{ session('nombre') }} | 
            <a href="{{ route('logout') }}">Cerrar Sesión</a>
        </div>
    </div>

    <div class="container">
        <h1>Directorio de Empleados</h1>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre Completo</th>
                    <th>Fecha de Ingreso</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                @foreach($empleados as $empleado)
                <tr>
                    <td>{{ $empleado->id }}</td>
                    <td>{{ $empleado->nombre_completo }}</td>
                    <td>{{ $empleado->fecha_ingreso }}</td>
                    <td>
                        <a href="{{ route('empleados.vacaciones', $empleado->id) }}" class="btn-calcular">Vacaciones</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>
</html>