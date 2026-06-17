<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrdersExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // 1. LA CONSULTA:
        // Aquí le decimos a Laravel qué datos obtener de la base de datos.
        // Incluimos las relaciones 'customer' y 'user' para poder usar sus nombres.
        return Order::with(['customer', 'user'])->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // 2. LAS CABECERAS:
        // Esto define los títulos de las columnas en el archivo Excel/CSV.
        return [
            'ID Orden',
            'Equipo',
            'Cliente',
            'Empleado Asignado',
            'Estado',
            'Fecha de Creación',
        ];
    }

    /**
     * @param Order $order
     * @return array
     */
    public function map($order): array
    {
        // 3. EL MAPEO:
        // Por cada orden que se encuentra, este método define qué dato
        // va en cada columna, en el mismo orden que las cabeceras.
        return [
            $order->id,
            $order->name_equip,
            $order->customer->fullname ?? 'N/A', // Obtenemos el nombre del cliente desde la relación
            $order->user?->name ?? 'Sin asignar',         // Obtenemos el nombre del empleado desde la relación
            $order->status,
            $order->created_at?->format('Y-m-d H:i:s') ?? 'N/A', // Formateamos la fecha
        ];
    }
}