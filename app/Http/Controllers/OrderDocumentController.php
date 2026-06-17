<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Company;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderDocumentController extends Controller
{
    /**
     * Genera un recibo de pago o factura en PDF.
     */
    public function generatePaymentReceipt(Order $order)
    {
        // Cargar las relaciones correctas
        $order->load(['customer', 'payments']); 
        
        $review = $order->reviews()->with('products')->first();
        $company = Company::first();

        // Calcular subtotales e impuestos
        $total = $review ? (float)$review->budget : 0.0;
        $subtotal = $total / 1.07;
        $tax = $total - $subtotal;

        $pdf = Pdf::loadView('pdfs.recibo_de_pago', compact('order', 'review', 'company', 'subtotal', 'tax', 'total'));

        // Retorna el PDF en el navegador con un nombre de archivo dinámico.
        return $pdf->stream('recibo-pago-'.$order->id.'.pdf');
    }

    /**
     * Genera una confirmación de retiro en PDF.
     */
    public function generatePickupConfirmation(Order $order)
    {
        $order->load(['customer']);
        
        $review = $order->reviews()->with('products')->first();
        $company = Company::first();

        $pdf = Pdf::loadView('pdfs.recibo_confirmacion', compact('order', 'review', 'company'));
        
        return $pdf->stream('confirmacion-retiro-'.$order->id.'.pdf');
    }

    /**
     * Genera una orden de entrega en PDF.
     */
    public function generateDeliveryOrder(Order $order)
    {
        $order->load(['customer']);

        $review = $order->reviews()->with('products')->first();
        $company = Company::first();

        $pdf = Pdf::loadView('pdfs.recibo_delivery', compact('order', 'review', 'company'));
        
        return $pdf->stream('orden-entrega-'.$order->id.'.pdf');
    }
}