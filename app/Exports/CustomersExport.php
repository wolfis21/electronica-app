<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomersExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        // Usamos withCount y with para cargar todos los datos necesarios en una sola consulta
        return Customer::withCount(['orders', 'payments'])->with('payments')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre Completo',
            'DNI/Cédula',
            'Teléfono',
            'Email',
            'Dirección',
            'Empresa',
            'Cantidad de Órdenes',
            'Cantidad de Pagos',
            'Métodos de Pago Usados',
            'Fecha de Creación',
        ];
    }

    public function map($customer): array
    {
        return [
            $customer->id,
            $customer->fullname,
            $customer->dni,
            $customer->phone,
            $customer->email,
            $customer->address,
            $customer->name_company,
            $customer->orders_count, // Usamos la propiedad generada por withCount
            $customer->payments_count, // Usamos la propiedad generada por withCount
            $customer->payments->pluck('payment_method')->unique()->implode(', '),
            $customer->created_at->format('Y-m-d'),
        ];
    }
}