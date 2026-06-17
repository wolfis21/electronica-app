<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    /*     public function __construct()
    {
        $this->middleware('can:view_orders')->only('index', 'show'); // Cambiado a view_all_orders
        $this->middleware('can:create_orders')->only(['create', 'store']);
        $this->middleware('can:edit_orders')->only(['edit', 'update']); // Cambiado a edit_all_orders
        $this->middleware('can:delete_orders')->only('destroy');
    } */

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status'); // <-- Nuevo: Obtenemos el término de estado

        $orders = Order::with('customer', 'user', 'reviews')
            ->when($search, function ($query, $search) {
                $query->where('id', 'like', "%{$search}%")
                    ->orWhere('name_equip', 'like', "%{$search}%")
                    ->orWhere('serial', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('accessories', 'like', "%{$search}%")
                    ->orWhere('extra_notes', 'like', "%{$search}%")
                    // No es necesario orWhere('status', 'like', "%{$search}%") aquí si ya tienes un filtro específico para el estado
                    ->orWhereHas('customer', function ($q) use ($search) {
                        $q->where('fullname', 'like', "%{$search}%")
                            ->orWhere('dni', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%")
                            ->orWhere('address', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            })
            // Nuevo: Filtro por estado
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Orders/Index', [
            'orders' => $orders,
            'can' => [
                'view_orders' => auth()->user()->hasPermissionTo('view_orders'),
                'create_orders' => auth()->user()->hasPermissionTo('create_orders'),
                'edit_orders' => auth()->user()->hasPermissionTo('edit_orders'),
                'delete_orders' => auth()->user()->hasPermissionTo('delete_orders'),
            ],
            // Pasamos ambos filtros a la vista para que puedan ser inicializados
            'filters' => $request->only('search', 'status'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Carga solo los usuarios que pueden ser responsables de órdenes (ej. aquellos con rol "Técnico" o "Administrador")
        // Aquí obtengo todos los usuarios, pero puedes filtrar por rol si lo necesitas.
        $responsibleUsers = User::all()->map(fn($user) => [
            'id' => $user->id,
            'name' => $user->name,
        ]);

        return Inertia::render('Orders/Create', [
            'responsibleUsers' => $responsibleUsers, // Cambiado de 'users' a 'responsibleUsers' para claridad
            'currentUserId' => auth()->id(), // Para preseleccionar al usuario actual como responsable
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            // Validación de Cliente
            'customer_dni' => ['required', 'string', 'max:255'],
            // 'customer_found' no es un campo de la DB, solo un indicador frontend.
            // Usamos required_if para los campos del cliente si no fue encontrado.
            'customer_fullname' => ['required_if:customer_found,false', 'string', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:255'],
            'customer_address' => ['nullable', 'string', 'max:255'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'customer_name_company' => ['nullable', 'string', 'max:255'],

            // Validación de Orden
            'name_equip' => ['required', 'string', 'max:255'],
            'serial' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'accessories' => ['nullable', 'string'],
            'extra_notes' => ['nullable', 'string'],
            'status' => ['required', 'string', Rule::in(['Pendiente', 'En proceso', 'Completado', 'Cancelado'])],
            'users_id' => ['required', 'exists:users,id'], // Usuario responsable
        ]);

        DB::transaction(function () use ($request) {
            $customer = Customer::where('dni', $request->customer_dni)->first();

            if (!$customer) {
                // Si el cliente no existe, lo creamos
                $customer = Customer::create([
                    'fullname' => $request->customer_fullname,
                    'dni' => $request->customer_dni,
                    'phone' => $request->customer_phone,
                    'address' => $request->customer_address,
                    'email' => $request->customer_email,
                    'name_company' => $request->customer_name_company,
                ]);
            }

            Order::create([
                'customers_id' => $customer->id, // Usa customers_id
                'users_id' => $request->users_id, // Usa users_id para el responsable
                'name_equip' => $request->name_equip,
                'serial' => $request->serial,
                'description' => $request->description,
                'accessories' => $request->accessories,
                'extra_notes' => $request->extra_notes,
                'status' => $request->status,
            ]);
        });

        return redirect()->route('orders.index')->with('success', 'Orden y cliente (si es nuevo) creados exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        // Carga las relaciones necesarias para la vista de detalles
        $order->load(['customer', 'user', 'reviews.products']); // Carga la relación 'reviews'

        return Inertia::render('Orders/Show', [
            'order' => $order,
            'customer' => $order->customer,
            'user' => $order->user,
            'can' => [
                'edit_orders' => auth()->user()->can('edit_orders'),
                'edit_own_orders' => auth()->user()->can('edit_own_orders'),
                'delete_orders' => auth()->user()->can('delete_orders'),
                // Pasar los permisos de revisión también
                'create_reviews' => auth()->user()->can('create_reviews'),
                'view_reviews' => auth()->user()->can('view_reviews'),
                'edit_reviews' => auth()->user()->can('edit_reviews'),
                'delete_reviews' => auth()->user()->can('delete_reviews'),
            ],
        ]);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        $order->load('customer', 'user'); // Carga las relaciones
        $responsibleUsers = User::all()->map(fn($user) => [
            'id' => $user->id,
            'name' => $user->name,
        ]);

        return Inertia::render('Orders/Edit', [
            'order' => $order,
            'responsibleUsers' => $responsibleUsers, // Cambiado de 'users' a 'responsibleUsers'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            // Validación de Cliente (actualizamos los datos del cliente existente)
            'customer_fullname' => ['required', 'string', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:255'],
            'customer_address' => ['nullable', 'string', 'max:255'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'customer_name_company' => ['nullable', 'string', 'max:255'],

            // Validación de Orden
            'name_equip' => ['required', 'string', 'max:255'],
            'serial' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'accessories' => ['nullable', 'string'],
            'extra_notes' => ['nullable', 'string'],
            'status' => ['required', 'string', Rule::in(['Pendiente', 'En proceso', 'Completado', 'Cancelado'])],
            'users_id' => ['required', 'exists:users,id'], // Usuario responsable
        ]);

       DB::transaction(function () use ($request, $order) {
        // Guardamos el ID del responsable original ANTES de actualizar
        $originalUserId = $order->users_id;

        // Actualizar el cliente asociado a la orden
        $order->customer->update([
            'fullname' => $request->customer_fullname,
            'phone' => $request->customer_phone,
            'address' => $request->customer_address,
            'email' => $request->customer_email,
            'name_company' => $request->customer_name_company,
        ]);

        // Actualizar la orden con los nuevos datos
        $order->update($request->only([
            'name_equip',
            'serial',
            'description',
            'accessories',
            'extra_notes',
            'status',
            'users_id',
        ]));

    });
        
        return redirect()->route('orders.index')->with('success', 'Orden y cliente actualizados exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        DB::transaction(function () use ($order) {
            $order->delete();
            // Nota: El cliente no se elimina automáticamente por defecto con onDelete('cascade')
            // en la FK de orders. Si quieres eliminar el cliente si ya no tiene órdenes,
            // necesitarías lógica adicional aquí:
            // if ($order->customer && $order->customer->orders()->count() === 0) {
            //     $order->customer->delete();
            // }
        });

        return redirect()->route('orders.index')->with('success', 'Orden eliminada exitosamente.');
    }
    public function printPdf(Order $order)
    {
        $order->load(['customer', 'user']); 
        $company = Company::first();

        $customPaper = array(0, 0, 230, 600); // [min_x, min_y, max_x, max_y] en puntos (pts)

        $pdf = Pdf::loadView('pdfs.order_receipt', compact('order', 'company'));
        
        // Aplica el tamaño de papel personalizado
        $pdf->setPaper($customPaper); // O setPaper([0,0,226.77,1000], 'portrait'); si lo prefieres

        return $pdf->stream('orden-recepcion-' . $order->id . '.pdf', ['Attachment' => 0]);
    }

     // --- MÉTODOS PARA GENERAR PDFS (LÓGICA CORREGIDA) ---

    public function generatePaymentReceipt(Order $order)
    {
        // 1. Obtenemos la primera (y única) revisión de la orden.
        $review = $order->reviews()->firstOrFail();
        
        // 2. Cargamos los productos asociados a ESA revisión.
        $review->load('products');

        // 3. Pasamos la orden y LA REVISIÓN a la vista del PDF.
        $pdf = PDF::loadView('pdfs.payment_receipt', compact('order', 'review'));
        return $pdf->stream('recibo-pago-'.$order->id.'.pdf');
    }

    public function confirmPickup(Order $order)
    {
        // 1. Cambia el estado SOLO si es necesario
        if (!in_array($order->status, [ 'Cancelado'])) {
            $order->update(['status' => 'Completado']);
        }

        // 2. Carga las relaciones necesarias
        $order->load(['customer', 'user']);
        $review = $order->reviews()->with('products')->first(); // Carga la revisión y sus productos
        $company = Company::first();

        if (!$review) {
            return response('<h1>Error</h1><p>La orden no tiene una revisión técnica asociada.</p>', 404)
                    ->header('Content-Type', 'text/html');
        }

        // 3. 👇 CÁLCULO DINÁMICO DE COSTOS 👇
        $partsCost = 0;
        $serviceCost = 0;

        foreach ($review->products as $item) {
            $itemCost = $item->pivot->quantity * $item->pivot->price_at_time_of_review;
            if ($item->is_service) {
                $serviceCost += $itemCost; // Suma si es un servicio
            } else {
                $partsCost += $itemCost; // Suma si es un repuesto/producto
            }
        }

        $totalCost = $partsCost + $serviceCost;

        // 4. Pasa las nuevas variables a la vista del PDF
        $pdf = PDF::loadView('pdfs.delivery_confirmation', compact(
            'order', 
            'review', 
            'company',
            'partsCost',      // <--- Nuevo
            'serviceCost',    // <--- Nuevo
            'totalCost'       // <--- Nuevo
        ));

        $filename = 'confirmacion-retiro-' . $order->id . '.pdf';
        return $pdf->stream($filename);
    }
}
