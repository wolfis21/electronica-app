<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Product::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre',
            'Código',
            'Descripción',
            'Precio de Costo',
            'Precio de Venta',
            'Es un Servicio',
            'Stock',
        ];
    }

    public function map($product): array
    {
        return [
            $product->id,
            $product->name,
            $product->code,
            $product->description,
            $product->price,
            $product->price_sale,
            $product->is_service ? 'Sí' : 'No',
            $product->is_service ? 'N/A' : $product->stock,
        ];
    }
}