<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Busca el producto por su código, si existe lo actualiza, si no, lo crea.
        return Product::updateOrCreate(
            ['code' => $row['codigo']],
            [
                'name'        => $row['nombre'],
                'description' => $row['descripcion'],
                'price'       => $row['precio_costo'],
                'price_sale'  => $row['precio_venta'],
                'is_service'  => filter_var($row['es_servicio_si_no'], FILTER_VALIDATE_BOOLEAN),
                'stock'       => $row['es_servicio_si_no'] ? null : $row['stock'],
            ]
        );
    }
}