<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Employee;
use App\Models\Company;
use Illuminate\Support\Facades\Hash;

class DemoUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $demoRole = Role::where('name', 'Demo')->first();

        if (!$demoRole) {
            $this->command->error('El rol "Demo" no existe. Asegúrate de ejecutar RoleSeeder primero.');
            return;
        }

        $company = Company::first();

        if (!$company) {
            $this->command->error('La empresa principal no existe. Ejecuta CompanySeeder primero.');
            return;
        }

        // Crear un empleado de prueba para el Demo
        $demoEmployee = Employee::firstOrCreate(
            ['dni' => 'V-00000000'],
            [
                'fullname' => 'Usuario Demo',
                'phone' => '0414-0000000',
                'dni' => 'V-00000000',
                'address' => 'Sistema de Demostración',
                'companies_id' => $company->id,
            ]
        );

        User::firstOrCreate(
            ['email' => 'demo@electronica.com'],
            [
                'name' => 'demo',
                'email' => 'demo@electronica.com',
                'password' => Hash::make('123456789'),
                'employees_id' => $demoEmployee->id,
                'role_id' => $demoRole->id,
                'email_verified_at' => now(),
            ]
        );
    }
}
