<style>
    body { 
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
        background-color: #f3f4f6; 
        margin: 0; 
    }

    /* --- Contenedor con Redondeado de 50px (Estilo Referencia) --- */
    .table-uco-container {
        background: #ffffff !important; 
        border-radius: 50px !important; /* REDONDEADO DE 50PX */
        overflow: hidden !important; /* Recorta la tabla para que no se salgan las esquinas */
        border: 1px solid #e5e7eb !important;
        box-shadow: 0 10px 25px rgba(52, 12, 81, 0.02) !important;
        padding: 0 !important; /* Permite que el encabezado toque los bordes */
        margin-bottom: 24px;
    }

    /* Encabezado interno de la tabla en Morado UCO */
    .table-uco-header {
        background-color: #340C51; /* MORADO INSTITUCIONAL */
        padding: 20px 40px;
    }

    .table-uco-container strong { 
        display: block; 
        color: #ffffff !important;
        font-size: 22px;
        font-weight: 700;
        text-align: left !important; 
        margin: 0 !important;
    }

    /* --- Estructura limpia de la Tabla --- */
    .alert-table { 
        width: 100%; 
        border-collapse: separate !important; /* Cambiado para permitir redondeados limpios */
        border-spacing: 0;
        margin-top: 0 !important; 
    }

    .alert-table th, .alert-table td { 
        text-align: left; 
        padding: 14px 40px; /* Más espacio interno para que luzca limpio */
        border-bottom: 1px solid #e5e7eb; 
    }

    /* Encabezados de columnas con sutil fondo gris e iconos/texto en Morado */
    .alert-table th { 
        background: #f9fafb; 
        color: #340C51; 
        font-weight: 700;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Elimina la línea inferior del último registro para que no rompa la curva inferior */
    .alert-table tr:last-child td {
        border-bottom: none;
    }

    /* --- Botón de Acción en Dorado UCO --- */
    .btn-calcular { 
        background-color: #AA7F31; /* DORADO INSTITUCIONAL */
        color: white; 
        padding: 8px 20px; 
        border-radius: 50px; /* Botón píldora súper redondeado */
        text-decoration: none; 
        font-size: 14px; 
        font-weight: 600;
        display: inline-block;
        transition: all 0.2s ease;
        box-shadow: 0 4px 10px rgba(170, 127, 49, 0.15);
    }

    .btn-calcular:hover { 
        background-color: #8c6827; 
        transform: scale(1.05); /* Efecto de crecimiento sutil al pasar el mouse */
    }
</style>
