<?php

namespace App\Imports;

use App\Models\Order;
use App\Models\Customer;
use App\Models\User; // Asegúrate de tener el modelo User
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows; // Para saltar filas completamente vacías
// REMOVIDO: use Maatwebsite\Excel\Concerns\WithUpserts; // Esta línea se elimina o comenta

class OrdersImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading, SkipsEmptyRows // REMOVIDO: , WithUpserts
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // === 1. Validación y Extracción de Datos ===
        // Los encabezados que nos proporcionaste: dni, fullname, name_equip, serial, description,
        // accessories, extra_notes, status, user_id, created_at, updated_at

        // Si el DNI o el nombre completo del cliente, o el nombre del equipo, o el user_id están vacíos,
        // asumimos que la fila no es válida para una orden.
        if (empty($row['dni']) || empty($row['fullname']) || empty($row['name_equip']) || empty($row['user_id'])) {
            // Puedes loggear el error o recopilarlo para un reporte final.
            // \Log::warning("Fila omitida debido a datos incompletos: " . json_encode($row));
            return null; // Saltar esta fila
        }

        // === 2. Procesar Cliente ===
        // Intentar encontrar el cliente por DNI o crearlo si no existe.
        // firstOrCreate actualizará los datos del cliente si el DNI ya existe.
        $customer = Customer::firstOrCreate(
            ['dni' => $row['dni']], // Criterio de búsqueda
            [
                'fullname' => $row['fullname'],
                'phone' => $row['phone'] ?? null,
                'email' => $row['email'] ?? null,
                'address' => $row['address'] ?? null,
                'name_company' => $row['name_company'] ?? null,
            ]
        );

        // === 3. Procesar Usuario ===
        // Verificar que el user_id exista en la tabla de usuarios.
        $user = User::find($row['user_id']);

        if (!$user) {
            // El user_id no existe. Ignorar esta orden o asignar a un usuario por defecto.
            // \Log::warning("User ID {$row['user_id']} no encontrado para la orden '{$row['name_equip']}'. Saltando orden.");
            return null; // Saltar esta fila de orden si el usuario no es válido.
        }

        // === 4. Validar y Asignar Estado ===
        $validStatuses = ['Pendiente', 'En proceso', 'Completado', 'Cancelado'];
        $status = $row['status'] ?? 'Pendiente'; // Valor por defecto si no se proporciona
        if (!in_array($status, $validStatuses)) {
            $status = 'Pendiente'; // Asignar un estado por defecto si el del archivo es inválido
        }

        // === 5. Crear la Orden ===
        $orderData = [
            'customers_id' => $customer->id,
            'users_id' => $user->id,
            'name_equip' => $row['name_equip'],
            'serial' => $row['serial'] ?? null,
            'description' => $row['description'] ?? null,
            'accessories' => $row['accessories'] ?? null,
            'extra_notes' => $row['extra_notes'] ?? null,
            'status' => $status,
        ];

        // Añadir created_at y updated_at si están presentes en el archivo XLSX
        if (isset($row['created_at']) && !empty($row['created_at'])) {
            $orderData['created_at'] = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['created_at']);
        }
        if (isset($row['updated_at']) && !empty($row['updated_at'])) {
            $orderData['updated_at'] = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['updated_at']);
        }

        return new Order($orderData);
    }

    /**
     * Define el tamaño del lote para inserciones de órdenes.
     *
     * @return int
     */
    public function batchSize(): int
    {
        return 500; // Un buen número para lotes, ajusta según el rendimiento de tu servidor
    }

    /**
     * Define el tamaño de los bloques de lectura.
     *
     * @return int
     */
    public function chunkSize(): int
    {
        return 500; // Lee 500 filas en memoria a la vez
    }
}