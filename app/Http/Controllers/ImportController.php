<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\OrdersImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Throwable;

class ImportController extends Controller
{
    public function showImportForm()
    {
        return Inertia::render('Import/ImportForm');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls|max:10240',
        ]);

        $uploadedFile = $request->file('file');
        $fileName = 'import_' . uniqid() . '_' . $uploadedFile->getClientOriginalName();
        $path = 'imports';

        try {
            DB::beginTransaction();

            // 1. Guardar el archivo en storage/app/imports
            $filePath = Storage::disk('local')->putFileAs($path, $uploadedFile, $fileName);

            // --- PUNTO DE CONTROL CRÍTICO: VERIFICAR LA SALIDA DE putFileAs Y LA EXISTENCIA DEL ARCHIVO ---
            // ¡Este dd() debería aparecer en tu navegador y detener la ejecución!
            // Si NO aparece, significa que el fallo es aún ANTES de esta línea (muy improbable a estas alturas).
            // Si $filePath es null o false, significa que putFileAs falló al guardar el archivo.
            // Si file_exists es false, significa que el archivo no fue escrito.
            $absoluteFilePath = Storage::path($filePath); // Necesitamos esta ruta para file_exists
            dd(
                'Resultado de Storage::putFileAs (ruta relativa):', $filePath,
                'Ruta absoluta donde debería estar:', $absoluteFilePath,
                '¿Existe el archivo físicamente en esa ruta?', file_exists($absoluteFilePath),
                '¿El Storage Disk lo reconoce?', Storage::disk('local')->exists($filePath)
            );

            // Estas líneas se ejecutarán después de que hayas visto la salida del dd() y la hayas quitado
            // Log::info('--- INICIO DEPURACIÓN IMPORTACIÓN ---');
            // Log::info('Ruta relativa en disco: ' . $filePath);
            // Log::info('Ruta absoluta generada: ' . $absoluteFilePath);
            // Log::info('¿Existe el archivo en la ruta absoluta? ' . (file_exists($absoluteFilePath) ? 'Sí' : 'No'));
            // Log::info('¿Existe el archivo en el disco de Storage? ' . (Storage::disk('local')->exists($filePath) ? 'Sí' : 'No'));
            // Log::info('--- FIN DEPURACIÓN IMPORTACIÓN ---');

            // Excel::import(new OrdersImport, $filePath, 'local');

            DB::commit();

            // Storage::disk('local')->delete($filePath);

            return redirect()->route('orders.index')->with('success', 'Datos importados exitosamente.');

        } catch (Throwable $e) {
            DB::rollBack();
            if (isset($filePath) && Storage::disk('local')->exists($filePath)) {
                // Storage::disk('local')->delete($filePath);
            }

            Log::error("Error durante la importación: " . $e->getMessage() . " en " . $e->getFile() . " línea " . $e->getLine());
            return redirect()->back()->with('error', 'Error al importar los datos: ' . $e->getMessage() . ' (Línea: ' . $e->getLine() . ' en ' . $e->getFile() . ')');
        }
    }
}