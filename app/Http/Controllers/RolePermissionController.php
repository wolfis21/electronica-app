<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia; // Para renderizar componentes Inertia
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Validation\Rule;
use App\Models\User;

class RolePermissionController extends Controller
{
    
    public function __construct()
    {
        // Aplicar middleware de autenticación
        $this->middleware('auth'); // Aplica el middleware 'auth' a todas las acciones del controlador

        // Usar el permiso general 'manage_roles' para todas las acciones de este controlador
        // Esto significa que si un usuario tiene 'manage_roles', tendrá acceso a todas las funciones CRUD de roles.
        $this->middleware('can:manage_roles');
    }

    /**
     * Muestra la lista de roles con sus permisos.
     *
     
     * @return \Inertia\Response
     */
    public function index()
    {
        // Obtener todos los roles con sus permisos cargados
        $roles = Role::with('permissions')->get()->map(function ($role) {
            return [
                'id' => $role->id,
                'name' => $role->name,
                'description' => $role->description,
                'permissions' => $role->permissions->pluck('name')->toArray(), // Solo los nombres de los permisos
            ];
        });

        // Obtener todos los permisos disponibles (para la UI de asignación)
        $permissions = Permission::all()->map(function ($permission) {
            return [
                'id' => $permission->id,
                'name' => $permission->name,
                'description' => $permission->description,
            ];
        });

        return Inertia::render('RolesAndPermissions/Index', [
            'roles' => $roles,
            'permissions' => $permissions,
            'can' => [ // Pasa los permisos del usuario actual a la vista si usas un sistema de Gates/Policies
                'create_roles' => auth()->user()->can('create_roles'),
                'edit_roles' => auth()->user()->can('edit_roles'),
                'delete_roles' => auth()->user()->can('delete_roles'),
                // Agrega más permisos según tus necesidades
            ],
        ]);
    }

    /**
     * Muestra el formulario para crear un nuevo rol.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        return Inertia::render('RolesAndPermissions/CreateRole');
    }

    /**
     * Almacena un nuevo rol en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:roles,name'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        Role::create($request->all());

        return redirect()->route('roles.index')
                         ->with('success', 'Rol creado exitosamente.');
    }

    /**
     * Muestra el formulario para editar un rol y asignar/quitar permisos.
     *
     * @param  \App\Models\Role  $role
     * @return \Inertia\Response
     */
    public function edit(Role $role)
    {
        // Cargar los permisos asociados al rol
        $role->load('permissions');

        // Obtener todos los permisos disponibles
        $allPermissions = Permission::all()->map(function ($permission) {
            return [
                'id' => $permission->id,
                'name' => $permission->name,
                'description' => $permission->description,
            ];
        });

        return Inertia::render('RolesAndPermissions/EditRole', [
            'role' => [
                'id' => $role->id,
                'name' => $role->name,
                'description' => $role->description,
                'current_permissions' => $role->permissions->pluck('id')->toArray(), // IDs de permisos actuales
            ],
            'all_permissions' => $allPermissions,
        ]);
    }

    /**
     * Actualiza un rol existente y sus permisos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:50', Rule::unique('roles')->ignore($role->id)],
            'description' => ['nullable', 'string', 'max:255'],
            'permissions' => ['nullable', 'array'], // Array de IDs de permisos
            'permissions.*' => ['exists:permissions,id'], // Cada ID debe existir en la tabla de permisos
        ]);

        $role->update($request->except('permissions')); // Actualiza los campos del rol

        // Sincroniza los permisos (adjunta, desvincula o mantiene los que ya están)
        $role->permissions()->sync($request->input('permissions', [])); // Asegura que sea un array vacío si no se envían permisos

        return redirect()->route('roles.index')
                         ->with('success', 'Rol actualizado exitosamente.');
    }

    /**
     * Elimina un rol.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Role $role)
    {
        // Opcional: Verificar si el rol tiene usuarios asignados antes de eliminar
        // Esto es manejado por la FK en la DB (onDelete('restrict')), pero puedes añadir lógica aquí
        if ($role->users()->count() > 0) {
            return redirect()->back()->with('error', 'No se puede eliminar el rol porque tiene usuarios asignados.');
        }

        $role->delete();

        return redirect()->route('roles.index')
                         ->with('success', 'Rol eliminado exitosamente.');
    }
}