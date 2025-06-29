<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee; // Importa el modelo Employee
use App\Models\Company;  // Importa el modelo Company

class AdminEmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener la primera compañía (asumiendo que ya has corrido CompanySeeder)
        $company = Company::first();

        // Crear el empleado para el administrador
        Employee::firstOrCreate(
            ['dni' => 'V-99999999'], // Usar DNI como identificador único
            [
                'fullname' => 'Administrador Principal',
                'phone' => '0414-1234567',
                'dni' => 'V-99999999',
                'address' => 'Dirección del Administrador',
                'companies_id' => $company->id,
            ]
        );
    }
}
