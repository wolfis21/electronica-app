<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportarDatosAntiguos extends Command
{
    // Este es el nombre con el que llamarás al comando
    protected $signature = 'importar:datos-antiguos';

    protected $description = 'Importa la data del sistema anterior desde un archivo SQL';

    public function handle()
    {
        $this->info('Iniciando la importación de datos del sistema anterior...');

        // Desactivamos las restricciones de llaves foráneas para evitar errores
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        try {
            // Obtenemos la ruta completa al archivo SQL
            $rutaSql = database_path('/Import-temporal-Orders-Customer.sql');

            // Ejecutamos el contenido del archivo SQL
            DB::unprepared(file_get_contents($rutaSql));

            $this->info('¡Importación completada exitosamente!');

        } catch (\Exception $e) {
            $this->error('Ocurrió un error durante la importación:');
            $this->error($e->getMessage());
            // Es importante reactivar las llaves foráneas incluso si hay un error
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            return 1; // Retorna un código de error
        }
        
        // Reactivamos las restricciones de llaves foráneas
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        return 0; // Retorna éxito
    }
}