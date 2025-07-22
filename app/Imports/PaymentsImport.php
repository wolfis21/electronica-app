<?php

namespace App\Imports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PaymentsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Payment([
            'orders_id'        => $row['id_orden'],
            'payment_date'     => $row['fecha_de_pago_yyyy_mm_dd'],
            'amount'           => $row['monto'],
            'currency'         => $row['moneda'],
            'payment_method'   => $row['metodo_pago'],
            'status'           => $row['estado'],
        ]);
    }
}