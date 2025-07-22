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
        return Customer::all();
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
            $customer->created_at->format('Y-m-d'),
        ];
    }
}