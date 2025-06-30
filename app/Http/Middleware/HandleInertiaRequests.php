<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy; 

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user() ? [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                    'role_id' => $request->user()->role_id, // <--- Asegurarnos de que role_id esté aquí
                    'role' => $request->user()->role ? [ // Cargar la relación 'role' si existe
                        'id' => $request->user()->role->id,
                        'name' => $request->user()->role->name,
                    ] : null,
                    'can' => $request->user() ? [
                        // Usar tus métodos hasPermissionTo para verificar permisos
                        'manage_roles' => $request->user()->hasPermissionTo('manage_roles'),
                        'manage_companies' => $request->user()->hasPermissionTo('manage_companies'),
                        'add_users' => $request->user()->hasPermissionTo('add_users'),
                        'edit_users' => $request->user()->hasPermissionTo('edit_users'),
                        'delete_users' => $request->user()->hasPermissionTo('delete_users'),
                        'view_users' => $request->user()->hasPermissionTo('view_users'),

                                                // Permisos de Órdenes
                        'create_orders' => $request->user()->hasPermissionTo('create_orders'),
                        'edit_all_orders' => $request->user()->hasPermissionTo('edit_all_orders'),
                        'edit_own_orders' => $request->user()->hasPermissionTo('edit_own_orders'),
                        'delete_orders' => $request->user()->hasPermissionTo('delete_orders'),
                        'view_all_orders' => $request->user()->hasPermissionTo('view_all_orders'),
                        'view_own_orders' => $request->user()->hasPermissionTo('view_own_orders'),
                        'print_order' => $request->user()->hasPermissionTo('print_order'),

                        // Permisos de Clientes
                        'create_customers' => $request->user()->hasPermissionTo('create_customers'),
                        'edit_customers' => $request->user()->hasPermissionTo('edit_customers'),
                        'delete_customers' => $request->user()->hasPermissionTo('delete_customers'),
                        'view_customers' => $request->user()->hasPermissionTo('view_customers'),
                        //'manage_customers' => $request->user()->hasPermissionTo('manage_customers'),
                    ] : [],
                ] : null,
            ],
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
        ]);
    }
}
