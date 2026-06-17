<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $company = \App\Models\Company::first() ?? \App\Models\Company::create([
            'name' => 'Default Company C.A.',
            'phone' => '123456',
            'email' => 'default@company.com',
            'address' => 'Default Address',
        ]);

        $employee = \App\Models\Employee::create([
            'fullname' => $request->name,
            'dni' => 'V-' . rand(10000000, 99999999),
            'phone' => null,
            'address' => null,
            'companies_id' => $company->id,
        ]);

        $role = \App\Models\Role::where('name', 'Tecnico')->first() ?? \App\Models\Role::firstOrCreate(
            ['name' => 'Tecnico'],
            ['description' => 'Técnico de reparaciones']
        );

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'employees_id' => $employee->id,
            'role_id' => $role->id,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
