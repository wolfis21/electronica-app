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
        <h1>{{ $company->name ?? 'Nombre de la Empresa' }}</h1>
        <p>{{ $company->address ?? '' }}</p>
        <p><strong>Teléfono:</strong> {{ $company->phone ?? '' }} | <strong>Correo:</strong> {{ $company->email ?? '' }}</p>
    </div>

    <h2 style="text-align:center;">Orden de Entrega</h2>
    
    <p><strong>No. de Orden:</strong> {{ $order->id }}</p>
    <p><strong>Fecha de Orden:</strong> {{ $order->created_at->format('d-M-Y') }}</p>
    
    <div class="details-box">
        <h3>Información de Entrega</h3>
        <p><strong>Cliente:</strong> {{ $order->customer->fullname }}</p>
        <p><strong>Dirección:</strong> {{ $order->customer->address ?? 'N/A' }}</p>
        <p><strong>Teléfono:</strong> {{ $order->customer->phone ?? 'N/A' }}</p>
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
                 @if($review && $review->products)
                     @foreach($review->products as $product)
                     <tr>
                         <td>{{ $product->name }}</td>
                         <td>{{ $product->pivot->quantity }}</td>
                     </tr>
                     @endforeach
                 @else
                     <tr>
                         <td colspan="2">No hay repuestos o servicios registrados.</td>
                     </tr>
                 @endif
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