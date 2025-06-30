<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    public function __construct()
    {
        // Aplicar middlewares de Gates para la gestión de clientes
        $this->middleware('can:view_customers')->only(['index', 'show']); // Para ver lista y detalles
        $this->middleware('can:create_customers')->only(['create', 'store']); // Para crear
        $this->middleware('can:edit_customers')->only(['edit', 'update']);   // Para editar
        $this->middleware('can:delete_customers')->only('destroy'); // Para eliminar

        // El método searchByDni no requiere un permiso específico ya que es una utilidad
        // usada típicamente en el flujo de creación de órdenes para precargar datos.
        // Si quieres protegerlo, puedes añadirlo a los 'only' de 'view_customers' o crear uno específico.
        // Por ahora, lo mantenemos sin protección directa de gate aquí.
    }

    /**
     * Display a listing of the resource (Customers).
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Customer::query();

        if ($search) {
            $query->where('fullname', 'like', '%' . $search . '%')
                  ->orWhere('dni', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%');
        }

        $customers = $query->paginate(10)->withQueryString(); // Mantener parámetros de búsqueda en paginación

        return Inertia::render('Customers/Index', [
            'customers' => $customers,
            'filters' => $request->only('search'), // Pasar filtros actuales para mantener el estado de búsqueda
            'can' => [
                'create_customers' => auth()->user()->can('create_customers'),
                'edit_customers' => auth()->user()->can('edit_customers'),
                'delete_customers' => auth()->user()->can('delete_customers'),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource (Customer).
     */
    public function create()
    {
        return Inertia::render('Customers/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'fullname' => ['required', 'string', 'max:255'],
            'dni' => ['required', 'string', 'max:45', 'unique:customers,dni'], // DNI debe ser único
            'phone' => ['nullable', 'string', 'max:45'],
            'address' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:45'], // Asegurarse de que sea nullable
        ]);

        Customer::create($request->all());

        return redirect()->route('customers.index')->with('success', 'Cliente creado exitosamente.');
    }

    /**
     * Display the specified resource (Customer).
     * Nota: Este método 'show' podría ser usado para ver detalles de un cliente
     * si lo necesitas por separado de la lista o edición. Por ahora, la funcionalidad
     * principal es listar y editar, pero lo mantengo si se decide usar.
     */
    public function show(Customer $customer)
    {
        return Inertia::render('Customers/Show', [
            'customer' => $customer,
        ]);
    }


    /**
     * Show the form for editing the specified resource (Customer).
     */
    public function edit(Customer $customer)
    {
        return Inertia::render('Customers/Edit', [
            'customer' => $customer,
        ]);
    }

    /**
     * Update the specified resource in storage (Customer).
     */
    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'fullname' => ['required', 'string', 'max:255'],
            'dni' => ['required', 'string', 'max:45', Rule::unique('customers')->ignore($customer->id)], // DNI único, ignorando el actual
            'phone' => ['nullable', 'string', 'max:45'],
            'address' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:45'],
        ]);

        $customer->update($request->all());

        return redirect()->route('customers.index')->with('success', 'Cliente actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage (Customer).
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Cliente eliminado exitosamente.');
    }

    /**
     * Search for a customer by DNI (existing method, kept for order creation flow).
     */
    public function searchByDni($dni)
    {
        $customer = Customer::where('dni', $dni)->first();

        if ($customer) {
            return response()->json([
                'found' => true,
                'customer' => $customer,
            ]);
        } else {
            return response()->json([
                'found' => false,
            ]);
        }
    }
}