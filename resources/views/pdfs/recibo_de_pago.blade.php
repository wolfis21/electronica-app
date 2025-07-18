<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Recibo de Pago #{{ $order->id }}</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 12px;
            color: #333;
        }

        .container {
            width: 100%;
            margin: 0 auto;
        }

        .header,
        .footer {
            text-align: center;
        }

        .header h1 {
            margin: 0;
        }

        .header p {
            margin: 2px 0;
        }

        .details {
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .details table {
            width: 100%;
            border-collapse: collapse;
        }

        .details th,
        .details td {
            padding: 8px;
            text-align: left;
        }

        .items-table table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .items-table th {
            background-color: #f2f2f2;
        }

        .totals {
            float: right;
            width: 40%;
            margin-top: 20px;
        }

        .totals table {
            width: 100%;
        }

        .totals td {
            padding: 5px 8px;
        }
    </style>
</head>

<body>
    <div class="container">
        <table style="width:100%;">
            <tr>
                <td style="width:60%;">
                    <h1></h1>
                    <p><strong>Razón Social:</strong> </p>
                    <p></p>
                    <p></p>
                    <p><strong>RUC:</strong> </p>
                    <p><strong>Teléfono:</strong> </p>
                    <p><strong>Correo:</strong> </p>
                </td>
                <td style="width:40%; text-align: right; vertical-align: top;">
                    <h2>FACTURA</h2>
                    <p><strong>No. Factura:</strong> {{ $order->invoice_number ?? $order->id }}</p>
                    <p><strong>Fecha:</strong> {{ $order->created_at->format('d-M-Y') }}</p>
                </td>
            </tr>
        </table>

        <div class="details">
            <table style="width: 100%;">
                <tr>
                    <th style="width: 50%;">Cliente:</th>
                    <th style="width: 50%;">Pago:</th>
                </tr>
                <tr>
                    <td>
                        <p>{{ $order->customer_name }}</p>
                        <p><strong>ID:</strong> {{ $order->customer_id_number }}</p>
                    </td>
                    <td>
                        <p><strong>Método de pago:</strong> {{ $order->payment_method }}</p>
                        <p><strong>Total Pagado:</strong> ${{ number_format($order->total, 2) }}</p>
                    </td>
                </tr>
            </table>
        </div>

        <div class="items-table">
            <table>
                <thead>
                    <tr>
                        <th>Descripción</th>
                        <th style="width:15%;">Precio Unit.</th>
                        <th style="width:10%;">Cant.</th>
                        <th style="width:15%;">Monto</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($review->products as $product)
                        <tr>
                            <td>
                                <strong>{{ $product->name }}</strong>
                                <br>
                                <small style="color: #6c757d;">{{ $product->description }}</small>
                            </td>
                            <td>{{ $product->pivot->quantity }}</td>
                            <td>${{ number_format($product->pivot->price_at_time_of_review, 2) }}</td>
                            <td>${{ number_format($product->pivot->quantity * $product->pivot->price_at_time_of_review, 2) }}
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

        <div class="totals">
            <table>
                <tr>
                    <td><strong>Sub Total:</strong></td>
                    <td style="text-align:right;">${{ number_format($order->subtotal, 2) }}</td>
                </tr>
                <tr>
                    <td><strong>Impuesto (ITBMS 7%):</strong></td>
                    <td style="text-align:right;">${{ number_format($order->tax, 2) }}</td>
                </tr>
                <tr>
                    <td>
                        <h3>Total:</h3>
                    </td>
                    <td style="text-align:right;">
                        <h3>${{ number_format($order->total, 2) }}</h3>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>
