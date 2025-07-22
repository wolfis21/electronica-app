<?php

namespace App\Imports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Busca al cliente por su DNI/cédula, si existe lo actualiza, si no, lo crea.
        return Customer::updateOrCreate(
            ['dni' => $row['dni']],
            [
                'fullname'      => $row['nombre_completo'],
                'phone'         => $row['telefono'],
                'email'         => $row['email'],
                'address'       => $row['direccion'],
                'name_company'  => $row['empresa'],
            ]
        );
    }
}