<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\OrdersImport; // Importamos nuestra "receta" de importación

class ImportController extends Controller
{
    public function import(Request $request)
    {
        // 1. Validación:
        // Nos aseguramos de que el usuario haya subido un archivo válido.
        $request->validate([
            'import_file' => 'required|mimes:xlsx,csv',
        ]);

        // 2. Procesamiento:
        // Usamos la fachada de Excel para importar el archivo subido,
        // utilizando la "receta" que creamos (OrdersImport).
        Excel::import(new OrdersImport, $request->file('import_file'));
        
        // 3. Redirección:
        // Devolvemos al usuario a la página de exportación/importación con un mensaje de éxito.
        return redirect()->route('export.index')->with('success', '¡Datos importados exitosamente!');
    }
}