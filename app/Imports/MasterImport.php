<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MasterImport implements WithMultipleSheets 
{
    /**
     * Este método mapea el nombre de cada hoja de cálculo
     * a su clase de importación correspondiente.
     */
    public function sheets(): array
    {
        return [
            'Clientes' => new CustomersImport(),
            'Productos' => new ProductsImport(),
            'Pagos' => new PaymentsImport(),
            // Aquí puedes añadir más hojas en el futuro
        ];
    }
}