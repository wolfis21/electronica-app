<?php

namespace App\Imports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class OrdersImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Este método se ejecuta por cada fila del archivo Excel/CSV.
        // Mapea las columnas del archivo (usando el nombre de la cabecera) a los campos del modelo Order.
        return new Order([
            'name_equip'   => $row['equipo'],
            'status'       => $row['estado'],
            'customers_id' => $row['id_cliente'],
            'users_id'     => $row['id_empleado_asignado'],
            // Asegúrate de que los nombres ('equipo', 'estado', etc.) coincidan
            // exactamente con las cabeceras de tu archivo de plantilla.
        ]);
    }
}