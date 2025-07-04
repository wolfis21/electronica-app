<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User; // Asegúrate de que este 'use' esté presente

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        // --- Definición de Gates para todos los permisos ---

        // Permisos de Administración de Empresas
        Gate::define('manage_companies', function (User $user) {
            return $user->hasPermissionTo('manage_companies');
        });

        // Permisos de Usuarios/Empleados
        Gate::define('add_users', function (User $user) {
            return $user->hasPermissionTo('add_users');
        });
        Gate::define('edit_users', function (User $user) {
            return $user->hasPermissionTo('edit_users');
        });
        Gate::define('delete_users', function (User $user) {
            return $user->hasPermissionTo('delete_users');
        });
        Gate::define('view_users', function (User $user) {
            return $user->hasPermissionTo('view_users');
        });

        // Permisos de Roles (usando el permiso general que elegiste)
        Gate::define('manage_roles', function (User $user) {
            return $user->hasPermissionTo('manage_roles'); // Este es el que usarás en el controlador
        });

        // Permisos de Permisos (gestión de los permisos del sistema)
        Gate::define('manage_permissions', function (User $user) {
            return $user->hasPermissionTo('manage_permissions');
        });

        // Permisos de Órdenes
        Gate::define('create_orders', function (User $user) {
            return $user->hasPermissionTo('create_orders');
        });
        Gate::define('edit_all_orders', function (User $user) {
            return $user->hasPermissionTo('edit_all_orders');
        });
        Gate::define('edit_own_orders', function (User $user) {
            return $user->hasPermissionTo('edit_own_orders');
        });
        Gate::define('delete_orders', function (User $user) {
            return $user->hasPermissionTo('delete_orders');
        });
        Gate::define('view_all_orders', function (User $user) {
            return $user->hasPermissionTo('view_all_orders');
        });
        Gate::define('view_own_orders', function (User $user) {
            return $user->hasPermissionTo('view_own_orders');
        });
        Gate::define('print_order', function (User $user) {
            return $user->hasPermissionTo('print_order');
        });

        // Permisos de Clientes
        Gate::define('create_customers', function (User $user) {
            return $user->hasPermissionTo('create_customers');
        });
        Gate::define('edit_customers', function (User $user) {
            return $user->hasPermissionTo('edit_customers');
        });
        Gate::define('delete_customers', function (User $user) {
            return $user->hasPermissionTo('delete_customers');
        });
        Gate::define('view_customers', function (User $user) {
            return $user->hasPermissionTo('view_customers');
        });

        // Permisos de Revisiones y Presupuestos
        Gate::define('register_review_all_orders', function (User $user) {
            return $user->hasPermissionTo('register_review_all_orders');
        });
        Gate::define('register_budget_reviews', function (User $user) {
            return $user->hasPermissionTo('register_budget_reviews');
        });
        Gate::define('view_all_reviews', function (User $user) {
            return $user->hasPermissionTo('view_all_reviews');
        });
        Gate::define('edit_all_reviews', function (User $user) {
            return $user->hasPermissionTo('edit_all_reviews');
        });

                // Gates para Gestión de Revisiones v2
        Gate::define('view_reviews', function (User $user) {
            return $user->hasRole('Administrador') || $user->hasPermissionTo('view_reviews');
        });

        Gate::define('create_reviews', function (User $user) {
            return $user->hasRole('Administrador') || $user->hasPermissionTo('create_reviews');
        });

        Gate::define('edit_reviews', function (User $user) {
            return $user->hasRole('Administrador') || $user->hasPermissionTo('edit_reviews');
        });

        Gate::define('delete_reviews', function (User $user) {
            return $user->hasRole('Administrador') || $user->hasPermissionTo('delete_reviews');
        });

        // Permisos de Pagos
        Gate::define('create_payments', function (User $user) {
            return $user->hasPermissionTo('create_payments');
        });
        Gate::define('view_payments', function (User $user) {
            return $user->hasPermissionTo('view_payments');
        });
        Gate::define('edit_payments', function (User $user) {
            return $user->hasPermissionTo('edit_payments');
        });
        Gate::define('delete_payments', function (User $user) {
            return $user->hasPermissionTo('delete_payments');
        });

        // Permisos de Productos/Servicios
        Gate::define('view_products', function (User $user) {
            return $user->hasPermissionTo('view_products');
        });
        Gate::define('create_products', function (User $user) {
            return $user->hasPermissionTo('create_products');
        });
        Gate::define('edit_products', function (User $user) {
            return $user->hasPermissionTo('edit_products');
        });
        Gate::define('delete_products', function (User $user) {
            return $user->hasPermissionTo('delete_products');
        });

        // Otros
        Gate::define('initiate_session', function (User $user) {
            return $user->hasPermissionTo('initiate_session');
        });
    }
}