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
        <h1></h1>
        <p></p>
        <p><strong>RUC:</strong> | <strong>Teléfono:</strong> </p>
    </div>

    <h2 style="text-align:center;">Confirmación de Retiro</h2>
    
    <p><strong>No. de Orden:</strong> {{ $order->id }}</p>
    <p><strong>Fecha de Orden:</strong> {{ $order->created_at->format('d-M-Y') }}</p>
    <p><strong>Cliente:</strong> {{ $order->customer_name }}</p>

    <div class="details-box">
        <h3>Detalles de Retiro</h3>
        <p><strong>Sucursal:</strong> </p>
        <p></p>
        <p></p>
        <p><strong>Horario de Retiro:</strong> </p>
    </div>

    <div class="details-box">
        <h3>Productos a Retirar</h3>
        <ul>
            @foreach($order->items as $item)
                <li>{{ $item->description }} (Cantidad: {{ $item->quantity }})</li>
            @endforeach
        </ul>
    </div>
</body>
</html>