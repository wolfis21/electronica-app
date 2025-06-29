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
                'manage_companies',
                'add_users', 'edit_users', 'view_users', // Gerente puede gestionar usuarios/empleados
                'create_orders', 'edit_all_orders', 'delete_orders', 'view_all_orders', 'print_order',
                'create_customers', 'edit_customers', 'delete_customers', 'view_customers',
                'register_review_all_orders', 'register_budget_reviews', 'view_all_reviews', 'edit_all_reviews',
                'register_payments', 'view_payments',
                'register_products_services', 'edit_products_services', 'delete_products_services',
                'initiate_session'
            ];
            $managerPermissionIds = Permission::whereIn('name', $managerPermissions)->pluck('id')->toArray();
            $managerRole->permissions()->sync($managerPermissionIds);
        }

        // Asignar permisos al Técnico (basado en tu diagrama de CU)
        if ($technicianRole) {
            $technicianPermissions = [
                'initiate_session',
                'create_orders', 'edit_own_orders', 'view_own_orders', 'print_order',
                'create_customers', 'view_customers', // Puede crear clientes, verlos pero no editar/eliminar todos
                'register_review_all_orders', 'register_budget_reviews', // Puede registrar revisiones y presupuestos
                'register_payments', // Puede registrar pagos
            ];
            $technicianPermissionIds = Permission::whereIn('name', $technicianPermissions)->pluck('id')->toArray();
            $technicianRole->permissions()->sync($technicianPermissionIds);
        }
    }
}
