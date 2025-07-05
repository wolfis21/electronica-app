<?php

namespace App\Http\Controllers;

use App\Models\Order; // Asegúrate de tener un modelo 'Order'
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderDocumentController extends Controller
{
    /**
     * Genera un recibo de pago o factura en PDF.
     */
    public function generatePaymentReceipt(Order $order)
    {
        // Puedes cargar las relaciones que necesites, como los items de la orden
        $order->load('items'); 

        $pdf = Pdf::loadView('pdfs.payment_receipt', ['order' => $order]);

        // Retorna el PDF en el navegador con un nombre de archivo dinámico.
        // El método stream() lo muestra sin forzar la descarga.
        return $pdf->stream('recibo-pago-'.$order->id.'.pdf');
    }

    /**
     * Genera una confirmación de retiro en PDF.
     */
    public function generatePickupConfirmation(Order $order)
    {
        $order->load('items');

        $pdf = Pdf::loadView('pdfs.pickup_confirmation', ['order' => $order]);
        
        return $pdf->stream('confirmacion-retiro-'.$order->id.'.pdf');
    }

    /**
     * Genera una orden de entrega en PDF.
     * (Similar a la de retiro, pero con campos para dirección de envío)
     */
    public function generateDeliveryOrder(Order $order)
    {
        $order->load('items');

        $pdf = Pdf::loadView('pdfs.delivery_order', ['order' => $order]);
        
        return $pdf->stream('orden-entrega-'.$order->id.'.pdf');
    }
}