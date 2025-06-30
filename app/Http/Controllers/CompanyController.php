<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Inertia\Inertia; // Para renderizar las vistas de Inertia
use Illuminate\Support\Facades\Redirect; // Para redirecciones

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Aplica el middleware 'auth' a todas las acciones
        $this->middleware('can:manage_companies'); // Solo usuarios con 'manage_companies' pueden acceder
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // En un sistema real con múltiples compañías, esto sería más complejo.
        // Asumiremos por ahora que solo hay una o que el administrador gestiona todas.
        // Para empezar, podemos simplemente listar todas o la primera.
        $companies = Company::all();

        // Si solo esperas una compañía (por ejemplo, la información de "tu" empresa)
        $company = Company::first(); // Obtiene la primera compañía o null si no hay ninguna

        return Inertia::render('Companies/Index', [
            'companies' => $companies, // Podrías pasar todas las compañías
            'company' => $company,     // O solo la primera/la que se va a gestionar
            'can' => [
                'manage_companies' => auth()->user()->can('manage_companies'),
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        return Inertia::render('Companies/Edit', [
            'company' => $company,
            'can' => [
                'manage_companies' => auth()->user()->can('manage_companies'),
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255', 'unique:companies,email,' . $company->id],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        $company->update($request->all());

        return Redirect::route('companies.index')->with('success', 'Información de la empresa actualizada exitosamente.');
    }

    // No necesitamos create, store, show, destroy por ahora, pero podrías añadirlos si los requieres.
}