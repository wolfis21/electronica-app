<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\OrdersImport;
use App\Imports\ProductsImport;
use App\Imports\CustomersImport;
use App\Imports\PaymentsImport;

class ImportController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'import_file' => 'required|mimes:xlsx,csv',
            'type'        => 'required|string|in:orders,customers,products,payments', // Nuevo campo de validación
        ]);

        $type = $request->input('type');

        try {
            // --- ACTUALIZA EL SWITCH ---
            switch ($type) {
                case 'orders':
                    Excel::import(new OrdersImport, $request->file('import_file'));
                    break;
                case 'customers':
                    Excel::import(new CustomersImport, $request->file('import_file'));
                    break;
                case 'products':
                    Excel::import(new ProductsImport, $request->file('import_file'));
                    break;
                case 'payments':
                    Excel::import(new PaymentsImport, $request->file('import_file'));
                    break;
            }
        } catch (\Throwable $e) {
            return redirect()->route('export.index')->with('error', 'Error al importar los datos: ' . $e->getMessage());
        }

        return redirect()->route('export.index')->with('success', '¡Datos importados exitosamente!');
    }
}
