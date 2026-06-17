<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Confirmación de Retiro #{{ $order->id }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 25px; }
        .details-box { border: 1px solid #ddd; padding: 15px; margin-top: 20px; }
        .details-box h3 { margin-top: 0; border-bottom: 1px solid #eee; padding-bottom: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $company->name ?? 'Nombre de la Empresa' }}</h1>
        <p>{{ $company->address ?? '' }}</p>
        <p><strong>Teléfono:</strong> {{ $company->phone ?? '' }} | <strong>Correo:</strong> {{ $company->email ?? '' }}</p>
    </div>

    <h2 style="text-align:center;">Confirmación de Retiro</h2>
    
    <p><strong>No. de Orden:</strong> {{ $order->id }}</p>
    <p><strong>Fecha de Orden:</strong> {{ $order->created_at->format('d-M-Y') }}</p>
    <p><strong>Cliente:</strong> {{ $order->customer->fullname }}</p>

    <div class="details-box">
        <h3>Detalles de Retiro</h3>
        <p><strong>Sucursal:</strong> {{ $company->name ?? 'Sede Principal' }}</p>
        <p><strong>Dirección:</strong> {{ $company->address ?? 'Puerto Ordaz, Bolívar' }}</p>
        <p><strong>Horario de Retiro:</strong> Lunes a Viernes 8:00 AM - 5:00 PM</p>
    </div>

    <div class="details-box">
        <h3>Productos a Retirar</h3>
        <ul>
            @if($review && $review->products)
                @foreach($review->products as $product)
                    <li>{{ $product->name }} (Cantidad: {{ $product->pivot->quantity }})</li>
                @endforeach
            @else
                <li>No hay repuestos o servicios registrados.</li>
            @endif
        </ul>
    </div>
</body>
</html>