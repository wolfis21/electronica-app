<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Retiro - Orden #{{ $order->id }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; color: #333; }
        .container { width: 100%; margin: 0 auto; }
        .header, .footer { text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .header p { margin: 5px 0; }
        .content { margin-top: 20px; }
        h2 { font-size: 16px; border-bottom: 1px solid #ccc; padding-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .details { margin-bottom: 20px; }
        .details p { margin: 4px 0; }
        .signature { margin-top: 50px; }
        .signature-line { border-top: 1px solid #333; width: 250px; margin-top: 40px; }
        .text-right { text-align: right; }
        .total-section { margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            @if($company && $company->logo)
                @endif
            <h1>{{ $company->name ?? 'Nombre de la Empresa' }}</h1>
            <p>{{ $company->address ?? 'Dirección de la Empresa' }}</p>
            <p>Teléfono: {{ $company->phone ?? '' }} | Email: {{ $company->email ?? '' }}</p>
        </div>

        <div class="content">
            <h2>Confirmación de Retiro de Equipo</h2>
            <p class="text-right"><strong>Fecha:</strong> {{ now()->format('d/m/Y') }}</p>
            <p><strong>Orden de Servicio N°:</strong> {{ $order->id }}</p>

            <div class="details">
                <h3>Datos del Cliente</h3>
                <p><strong>Nombre:</strong> {{ $order->customer->fullname }}</p>
                <p><strong>DNI/RIF:</strong> {{ $order->customer->dni }}</p>
                <p><strong>Teléfono:</strong> {{ $order->customer->phone }}</p>
            </div>

            <div class="details">
                <h3>Detalles del Equipo</h3>
                <p><strong>Equipo:</strong> {{ $order->name_equip }}</p>
                <p><strong>Serial:</strong> {{ $order->serial ?? 'N/A' }}</p>
                <p><strong>Falla reportada por el cliente:</strong> {{ $order->description }}</p>
            </div>

            <div class="details">
                <h3>Diagnóstico y Trabajo Realizado</h3>
                <p>{{ $review->diagnostic }}</p>
            </div>

            @if($review->products->isNotEmpty())
                <h3>Repuestos y Materiales Utilizados</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Descripción</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($review->products as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->pivot->quantity }}</td>
                            <td>${{ number_format($product->pivot->price_at_time_of_review, 2) }}</td>
                            <td>${{ number_format($product->pivot->quantity * $product->pivot->price_at_time_of_review, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            <div class="total-section text-right">
                <p><strong>Costo del Servicio:</strong> ${{ number_format($serviceCost, 2) }}</p>
                <p><strong>Total Repuestos:</strong> ${{ number_format($partsCost, 2) }}</p>
                <h3><strong>Monto Total a Pagar: ${{ number_format($totalCost, 2) }}</strong></h3>
            </div>
            
            <p style="margin-top: 30px;">
                Yo, <strong>{{ $order->customer->fullname }}</strong>, titular de la cédula de identidad <strong>{{ $order->customer->dni }}</strong>, 
                confirmo haber recibido el equipo arriba descrito en conformidad y funcionando correctamente.
            </p>

            <div class="signature">
                <div class="signature-line"></div>
                <p>Firma del Cliente</p>
            </div>
        </div>

        <div class="footer">
            <p>Gracias por su confianza.</p>
        </div>
    </div>
</body>
</html>