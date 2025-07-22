<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Exports\OrdersExport; // 1. Importamos nuestra "receta" de exportación
use Maatwebsite\Excel\Facades\Excel; // 2. Importamos la fachada de la librería

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
        
        // Generamos un nombre de archivo dinámico con la fecha actual.
        $fileName = $type . '-' . now()->format('Y-m-d') . '.' . $format;
        
        // 4. LÓGICA DE SELECCIÓN:
        // Usamos un 'switch' para decidir qué clase de exportación usar.
        // Esto hace que sea muy fácil añadir más reportes en el futuro.
        switch ($type) {
            case 'orders':
                return Excel::download(new OrdersExport(), $fileName);
                break;
            
            // Futuros casos:
            // case 'customers':
            //     return Excel::download(new CustomersExport(), $fileName);
            //     break;
        }

        // Si el 'type' no es válido, redirigimos de vuelta.
        return redirect()->back()->with('error', 'Tipo de exportación no válido.');
    }
}