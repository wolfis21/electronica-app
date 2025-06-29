<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'name' => 'Administrador',
            'description' => 'Acceso total y control sobre todas las funcionalidades del sistema.',
        ]);

        Role::create([
            'name' => 'Gerente',
            'description' => 'Gestiona órdenes, clientes, productos y ve reportes.',
        ]);

        Role::create([
            'name' => 'Tecnico', 
            'description' => 'Realiza y ve órdenes asignadas, ve clientes y da reportes.',
        ]);
    }
}
