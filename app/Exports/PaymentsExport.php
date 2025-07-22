<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PaymentsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Payment::with('order.customer')->get();
    }

    public function headings(): array
    {
        return [
            'ID Pago',
            'ID Orden',
            'Cliente',
            'Fecha de Pago',
            'Monto',
            'Moneda',
            'Método de Pago',
            'Estado',
        ];
    }

    public function map($payment): array
    {
        return [
            $payment->id,
            $payment->orders_id,
            $payment->order->customer->fullname ?? 'N/A',
            $payment->payment_date,
            $payment->amount,
            $payment->currency,
            $payment->payment_method,
            $payment->status,
        ];
    }
}