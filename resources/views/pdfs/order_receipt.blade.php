<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>Orden #{{ $order->id }}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            text-transform: uppercase;
            font-size: 12x; /* Mantener pequeño */
            line-height: 1;
            color: #000;
            margin: 0;
            padding: 0;
        }
        .container {
            margin: 0;
            padding: 0; /* Eliminar padding aquí, los elementos internos tendrán su propio margen */
            box-sizing: border-box;
        }
        .header {
            text-align: center;
            margin-bottom: 4px; /* Un poco menos de margen */
            padding: 2mm 0 2mm 0; /* Padding superior e inferior, 0 lateral */
            border-bottom: 1px dashed #000;
        }
        .header h1 {
            font-size: 12px; /* Título principal aún más compacto */
            margin: 0;
            padding: 0;
            line-height: 1;
        }
        .header p {
            font-size: 12px;
            margin: 1px 0;
        }

        .section {
            margin-bottom: 4px; /* Menos espacio entre secciones */
            padding: 0; /* Padding lateral de 1mm para que el contenido no se pegue al borde */
            background-color: transparent;
        }
        .section h3 {
            font-size: 12px; /* Títulos de sección */
            border-bottom: 1px dashed #000;
            padding-bottom: 1px; /* Menos padding */
            margin-top: 4px; /* Margen superior ajustado */
            margin-bottom: 3px; /* Menos espacio bajo el título */
            text-transform: uppercase;
            text-align: center;
        }
        .section p {
            margin: 0 0 1px 0; /* Espaciado mínimo entre líneas */
            font-size: 12px;
            word-wrap: break-word;
            text-align: left;
        }

        .status-badge { /* No usado en el HTML actual, pero mantenido */
            display: inline-block;
            padding: 0px 2px;
            border-radius: 2px;
            font-weight: bold;
            font-size: 12px;
            text-transform: capitalize;
            color: #000;
            background-color: #eee;
            border: 1px solid #ccc;
        }

        .footer {
            text-align: center;
            margin-top: 8px; /* Menos margen antes del footer */
            font-size: 12px;
            color: #333;
            border-top: 1px dashed #000;
            padding-top: 4px; /* Menos padding */
            padding-bottom: 2mm; /* Pequeño padding al final del recibo */
        }
        .footer p {
            margin: 0;
            line-height: 1.1;
        }
        .signature-area {
            margin-top: 20px; /* Menos espacio antes de las firmas */
            margin-bottom: 20px;
        }
        .signature-line {
            width: 80%;
            margin: 8px auto 1px auto; /* Espaciado ajustado */
            border-bottom: 1px dashed #000;
        }
        .signature-label {
            font-size: 12px;
            text-align: center;
            display: block;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $company->name ?? 'Nombre de la Empresa' }}</h1>
            <p>{{ $company->address ?? 'Dirección de la Empresa' }}</p>
            <p>{{ $company->phone ?? 'N/A' }}</p>
            <p>{{ \Carbon\Carbon::now()->format('d/m/Y, H:i:s') }}</p>
        </div>

        <div class="section">
            <p><strong>Orden #:</strong>{{ $order->id }}</p>
            <p><strong>Equipo:</strong> {{ $order->name_equip }}</p>
            <p><strong>Serial:</strong> {{ $order->serial ?? 'N/A' }}</p>
            <p><strong>Motivo:</strong> {{ $order->description ?? 'N/A' }}</p>
            <p><strong>Accesorios:</strong> {{ $order->accessories ?? 'N/A' }}</p>
            <p><strong>Nota:</strong> {{ $order->extra_notes ?? 'N/A' }}</p>
        </div>

        <div class="section">
            <h3>Cliente</h3>
            <p><strong>Nombre:</strong> {{ $order->customer->fullname }}</p>
            <p><strong>Cedula/RIF:</strong> {{ $order->customer->dni }}</p>
            <p><strong>Empresa:</strong> {{ $order->customer->name_company ?? 'N/A' }}</p>
            <p><strong>Telefono:</strong> {{ $order->customer->phone ?? 'N/A' }}</p>
            <p><strong>Email:</strong> {{ $order->customer->email ?? 'N/A' }}</p>
            <p><strong>Direccion:</strong> {{ $order->customer->address ?? 'N/A' }}</p>
        </div>

        <div class="section">
            <h3>Atendido por: {{ $order->user->name }} </h3>
        </div>

        <div class="footer">
            <div class="signature-area">
                <div class="signature-line"></div>
                <span class="signature-label">Firma Cliente</span>
            </div>

            <p style="margin-top: 5px;">¡Gracias por su confianza!</p>
        </div>
    </div>
</body>
</html>