<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Orden de Entrega #{{ $order->id }}</title>
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
    </div>

    <h2 style="text-align:center;">Orden de Entrega</h2>
    
    <p><strong>No. de Orden:</strong> {{ $order->id }}</p>
    <p><strong>Fecha de Orden:</strong> {{ $order->created_at->format('d-M-Y') }}</p>
    
    <div class="details-box">
        <h3>Información de Entrega</h3>
        <p><strong>Cliente:</strong> {{ $order->customer_name }}</p>
        <p><strong>Dirección:</strong> {{ $order->delivery_address }}</p>
        <p><strong>Teléfono:</strong> {{ $order->customer_phone }}</p>
    </div>

    <div class="details-box">
        <h3>Productos a Entregar</h3>
        <table style="width:100%;">
            <thead>
                <tr>
                    <th style="text-align:left;">Descripción</th>
                    <th style="text-align:left; width:15%;">Cantidad</th>
                </tr>
            </thead>
            <tbody>
                 @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td>{{ $item->quantity }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="details-box">
        <p><strong>Recibido por:</strong> _________________________</p>
        <br>
        <p><strong>Firma:</strong> _________________________</p>
        <br>
        <p><strong>Fecha de Recibido:</strong> ____ / ____ / ________</p>
    </div>
</body>
</html>