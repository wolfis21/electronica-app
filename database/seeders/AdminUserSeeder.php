<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;     // Importa el modelo User
use App\Models\Role;     // Importa el modelo Role
use App\Models\Employee; // Importa el modelo Employee
use Illuminate\Support\Facades\Hash; // Para hashear la contraseña

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el rol de Administrador
        $adminRole = Role::where('name', 'Administrador')->first();

        // Obtener el empleado del Administrador
        $adminEmployee = Employee::where('dni', 'V-99999999')->first();

        if (!$adminRole) {
            $this->command->error('El rol "Administrador" no existe. Asegúrate de ejecutar RoleSeeder primero.');
            return;
        }

        if (!$adminEmployee) {
            $this->command->error('El empleado del Administrador no existe. Asegúrate de ejecutar AdminEmployeeSeeder primero.');
            return;
        }

        User::firstOrCreate(
            ['email' => 'admin@electronica.com'], // Usar email como identificador único para el usuario
            [
                'name' => 'admin',
                'email' => 'admin@electronicatplkg.com',
                'password' => Hash::make('123456789'), // ¡CAMBIA ESTA CONTRASEÑA EN PRODUCCIÓN!
                'employees_id' => $adminEmployee->id,
                'role_id' => $adminRole->id,
                'email_verified_at' => now(), // Marcar como verificado
            ]
        );

    }
}
