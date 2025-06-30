<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use App\Models\Company;
use App\Models\Role; // <--- Importa tu modelo Role
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
// No Spatie\Permission\Models\Role; // Ya no necesitamos Spatie

class EmployeeUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource (Employees with Users).
     */
    public function index()
    {
        // Carga los empleados con sus usuarios y roles
        $employees = Employee::with('user.role')->paginate(10); // Carga user.role

        // Usamos los métodos hasPermissionTo de tu modelo User
        return Inertia::render('EmployeesUsers/Index', [
            'employees' => $employees,
            'can' => [
                'add_users' => auth()->user()->hasPermissionTo('add_users'),
                'edit_users' => auth()->user()->hasPermissionTo('edit_users'),
                'delete_users' => auth()->user()->hasPermissionTo('delete_users'),
                'view_users' => auth()->user()->hasPermissionTo('view_users'),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource (Employee and User).
     */
    public function create()
    {
        // Verificar permiso para crear usuarios
        $this->authorize('add_users'); // Usa el Gate/Policy configurado en AuthServiceProvider

        $roles = Role::all()->map(fn ($role) => [
            'id' => $role->id,
            'name' => $role->name,
        ]);

        $companies = Company::all()->map(fn ($company) => [
            'id' => $company->id,
            'name' => $company->name,
        ]);

        return Inertia::render('EmployeesUsers/Create', [
            'roles' => $roles,
            'companies' => $companies,
            'can' => [
                'add_users' => auth()->user()->hasPermissionTo('add_users'),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage (Employee and User).
     */
    public function store(Request $request)
    {
        $this->authorize('add_users');

        $request->validate([
            'fullname' => ['required', 'string', 'max:255'],
            'dni' => ['required', 'string', 'max:20', 'unique:employees,dni'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'companies_id' => ['required', 'exists:companies,id'],

            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role_id' => ['required', 'exists:roles,id'],
        ]);

        DB::transaction(function () use ($request) {
            $employee = Employee::create([
                'fullname' => $request->fullname,
                'dni' => $request->dni,
                'phone' => $request->phone,
                'address' => $request->address,
                'companies_id' => $request->companies_id,
            ]);

            $user = User::create([
                'name' => $request->fullname,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'employees_id' => $employee->id,
                'role_id' => $request->role_id, // <--- Asignar el role_id directamente
            ]);

            // No user->assignRole($role); aquí, ya lo asignamos directamente
        });

        return redirect()->route('employees_users.index')->with('success', 'Empleado y Usuario creados exitosamente.');
    }

    /**
     * Show the form for editing the specified resource (Employee and User).
     */
    public function edit(Employee $employee)
    {
        $employee->load('user.role'); // Carga la relación 'user' y dentro de 'user' la relación 'role'



        $this->authorize('edit_users');

        $roles = Role::all()->map(fn ($role) => [
            'id' => $role->id,
            'name' => $role->name,
        ]);

        $companies = Company::all()->map(fn ($company) => [
            'id' => $company->id,
            'name' => $company->name,
        ]);

        return Inertia::render('EmployeesUsers/Edit', [
            'employee' => $employee,
            'user' => $employee->user,
            'currentRoleId' => $employee->user->role_id,
            'roles' => $roles,
            'companies' => $companies,
            'can' => [
                'edit_users' => auth()->user()->hasPermissionTo('edit_users'),
            ],
        ]);
    }

    /**
     * Update the specified resource in storage (Employee and User).
     */
    public function update(Request $request, Employee $employee)
    {
        $this->authorize('edit_users');

        $user = $employee->user;

        $request->validate([
            'fullname' => ['required', 'string', 'max:255'],
            'dni' => ['required', 'string', 'max:20', Rule::unique('employees')->ignore($employee->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'companies_id' => ['required', 'exists:companies,id'],

            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role_id' => ['required', 'exists:roles,id'],
        ]);

        DB::transaction(function () use ($request, $employee, $user) {
            $employee->update([
                'fullname' => $request->fullname,
                'dni' => $request->dni,
                'phone' => $request->phone,
                'address' => $request->address,
                'companies_id' => $request->companies_id,
            ]);

            $userData = [
                'name' => $request->fullname,
                'email' => $request->email,
                'role_id' => $request->role_id, // <--- Actualizar el role_id directamente
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $user->update($userData);
        });

        return redirect()->route('employees_users.index')->with('success', 'Empleado y Usuario actualizados exitosamente.');
    }

    /**
     * Remove the specified resource from storage (Employee and User).
     */
    public function destroy(Employee $employee)
    {
        $this->authorize('delete_users');

        DB::transaction(function () use ($employee) {
            if ($employee->user) {
                // Eliminar el usuario (esto también desvinculará su role_id)
                $employee->user->delete();
            }
            $employee->delete();
        });

        return redirect()->route('employees_users.index')->with('success', 'Empleado y Usuario eliminados exitosamente.');
    }
}