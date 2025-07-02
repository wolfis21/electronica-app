<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Permisos de Administración de Empresas
        Permission::create(['name' => 'manage_companies', 'description' => 'Gestionar datos de empresa']);

        // Permisos de Usuarios/Empleados
        Permission::create(['name' => 'add_users', 'description' => 'Añadir nuevos empleados/usuarios']);
        Permission::create(['name' => 'edit_users', 'description' => 'Editar empleados/usuarios existentes']);
        Permission::create(['name' => 'delete_users', 'description' => 'Eliminar empleados/usuarios']);
        Permission::create(['name' => 'view_users', 'description' => 'Ver lista de empleados/usuarios']);

        // Permisos de Roles
        Permission::create(['name' => 'manage_roles', 'description' => 'Gestionar roles de usuario']);

        // Permisos de Permisos
        Permission::create(['name' => 'manage_permissions', 'description' => 'Gestionar los permisos del sistema']);

        // Permisos de Órdenes
        Permission::create(['name' => 'create_orders', 'description' => 'Crear nuevas órdenes de servicio']);
        Permission::create(['name' => 'edit_all_orders', 'description' => 'Editar todas las órdenes (cualquier usuario)']);
        Permission::create(['name' => 'edit_own_orders', 'description' => 'Editar órdenes creadas por el propio usuario']);
        Permission::create(['name' => 'delete_orders', 'description' => 'Eliminar órdenes de servicio']);
        Permission::create(['name' => 'view_all_orders', 'description' => 'Ver todas las órdenes de servicio']);
        Permission::create(['name' => 'view_own_orders', 'description' => 'Ver órdenes creadas por el propio usuario']);
        Permission::create(['name' => 'print_order', 'description' => 'Imprimir detalles de una orden']);

        // Permisos de Clientes
        Permission::create(['name' => 'create_customers', 'description' => 'Crear nuevos clientes']);
        Permission::create(['name' => 'edit_customers', 'description' => 'Editar información de clientes']);
        Permission::create(['name' => 'delete_customers', 'description' => 'Eliminar clientes']);
        Permission::create(['name' => 'view_customers', 'description' => 'Ver lista de clientes']);

        // Permisos de Revisiones y Presupuestos
        Permission::create(['name' => 'view_reviews', 'description' => 'Ver todas las revisiones de órdenes']);
        Permission::create(['name' => 'create_reviews', 'description' => 'Registrar revisión en todas las órdenes']);
        Permission::create(['name' => 'edit_reviews', 'description' => 'Editar las revisiones de todas las órdenes']);
        Permission::create(['name' => 'delete_reviews', 'description' => 'Registrar presupuesto en revisiones']);
        

        // Permisos de Pagos
        Permission::create(['name' => 'register_payments', 'description' => 'Registrar pagos recibidos de órdenes']);
        Permission::create(['name' => 'view_payments', 'description' => 'Ver pagos recibidos']);

        // Permisos de Productos/Servicios
        Permission::create(['name' => 'create_products', 'description' => 'Crear productos/servicios']);
        Permission::create(['name' => 'view_products', 'description' => 'Ver productos/servicios']);
        Permission::create(['name' => 'edit_products', 'description' => 'Editar productos/servicios']);
        Permission::create(['name' => 'delete_products', 'description' => 'Eliminar productos/servicios']);

        // Otros
        Permission::create(['name' => 'initiate_session', 'description' => 'Iniciar sesión en el sistema']); // Aunque esto es básico, puede ser útil para un control granular.
    }
}
