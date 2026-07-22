<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CompanySeeder::class,      // Primero la compañía
            RoleSeeder::class,         // Luego los roles
            PermissionSeeder::class,   // Luego los permisos
            RolePermissionSeeder::class, // Después asignamos permisos a roles

            AdminEmployeeSeeder::class, // Luego el empleado administrador
            AdminUserSeeder::class,     // Y finalmente el usuario administrador
            DemoUserSeeder::class,      // Usuario Demo para visitantes
            ProductSeeder::class, // Inicializacion de servicios
            OrderSeeder::class, // Orders y customers de test
        ]);
    }
}
