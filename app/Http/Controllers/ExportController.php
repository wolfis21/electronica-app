<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Exports\OrdersExport;
use Maatwebsite\Excel\Facades\Excel; 
use App\Exports\CustomersExport;
use App\Exports\ProductsExport;
use App\Exports\PaymentsExport;

class ExportController extends Controller
{
    /**
     * Muestra la página principal con las opciones de exportación.
     */
    public function index()
    {
        // Simplemente renderiza el componente de Vue que creamos en el paso anterior.
        return Inertia::render('Export/Index');
    }

    /**
     * Procesa la solicitud de descarga.
     */
    public function download(Request $request)
    {
        // 3. VALIDACIÓN:
        // Nos aseguramos de que los datos que envía el formulario sean válidos.
        $request->validate([
            'type' => 'required|string|in:orders,customers,products', // Acepta solo estos valores
            'format' => 'required|string|in:xlsx,csv', // Acepta solo estos formatos
        ]);

        $type = $request->input('type');
        $format = $request->input('format');
        $fileName = $type . '-' . now()->format('Y-m-d') . '.' . $format;
        
        // --- ACTUALIZA EL SWITCH ---
        switch ($type) {
            case 'orders':
                return Excel::download(new OrdersExport(), $fileName);
                break;
            case 'customers': // <-- Nuevo
                return Excel::download(new CustomersExport(), $fileName);
                break;
            case 'products': // <-- Nuevo
                return Excel::download(new ProductsExport(), $fileName);
                break;
            case 'payments': // <-- Nuevo
                return Excel::download(new PaymentsExport(), $fileName);
                break;
        }

        // Si el 'type' no es válido, redirigimos de vuelta.
        return redirect()->back()->with('error', 'Tipo de exportación no válido.');
    }
}