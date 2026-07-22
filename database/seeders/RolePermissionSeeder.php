<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener los roles creados
        $adminRole = Role::where('name', 'Administrador')->first();
        $managerRole = Role::where('name', 'Gerente')->first();
        $technicianRole = Role::where('name', 'Tecnico')->first();

        // Obtener todos los permisos (o los que necesites asignar)
        $allPermissions = Permission::pluck('id')->toArray(); // Obtiene todos los IDs de permisos

        // Asignar permisos al Administrador (todos los permisos)
        if ($adminRole) {
            $adminRole->permissions()->sync($allPermissions); // Adjunta todos los permisos al rol de Administrador
        }

        // Asignar permisos al Gerente (basado en tu diagrama de CU)
        if ($managerRole) {
            $managerPermissions = [
                'add_users', 'edit_users', 'view_users', 'delete_users', // Gerente puede gestionar usuarios/empleados
                'create_orders', 'edit_orders', 'delete_orders', 'view_orders', 'print_order',
                'create_customers', 'edit_customers', 'delete_customers', 'view_customers',
                'create_reviews', 'view_reviews', 'edit_reviews', 'delete_reviews',
                'create_payments', 'view_payments', 'edit_payments', 'delete_payments',
                'create_products', 'edit_products', 'delete_products', 'view_products',
                'initiate_session'
            ];
            $managerPermissionIds = Permission::whereIn('name', $managerPermissions)->pluck('id')->toArray();
            $managerRole->permissions()->sync($managerPermissionIds);
        }

        // Asignar permisos al Técnico (basado en tu diagrama de CU)
        if ($technicianRole) {
            $technicianPermissions = [
                'initiate_session',
                'create_orders', 'edit_orders', 'view_orders', 'print_order',
                'create_customers', 'view_customers', // Puede crear clientes, verlos pero no editar/eliminar todos
                'view_products', 'view_reviews',
            ];
            $technicianPermissionIds = Permission::whereIn('name', $technicianPermissions)->pluck('id')->toArray();
            $technicianRole->permissions()->sync($technicianPermissionIds);
        }

        $demoRole = Role::where('name', 'Demo')->first();

        // Asignar permisos al Demo
        if ($demoRole) {
            $demoPermissions = [
                'initiate_session',
                'view_users',
                'view_orders',
                'view_customers',
                'view_reviews',
                'view_payments',
                'view_products',
            ];
            $demoPermissionIds = Permission::whereIn('name', $demoPermissions)->pluck('id')->toArray();
            $demoRole->permissions()->sync($demoPermissionIds);
        }
    }
}
