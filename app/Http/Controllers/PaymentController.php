<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Order; // Necesitamos el modelo Order para asociar pagos
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Middleware para permisos de pagos
        $this->middleware('can:view_payments')->only(['index', 'show']);
        $this->middleware('can:create_payments')->only(['create', 'store']);
        $this->middleware('can:edit_payments')->only(['edit', 'update']);
        $this->middleware('can:delete_payments')->only('destroy');
    }

    /**
     * Muestra una lista de todos los pagos.
     */
    public function index()
    {
        // Carga los pagos con su orden asociada para mostrar detalles relevantes
        $payments = Payment::with('order.customer', 'order.user') // Carga la orden, y dentro de la orden, el cliente y el usuario
                            ->orderBy('payment_date', 'desc')
                            ->paginate(10);

        return Inertia::render('Payments/Index', [
            'payments' => $payments,
            'can' => [
                'create_payments' => auth()->user()->can('create_payments'),
                'edit_payments' => auth()->user()->can('edit_payments'),
                'delete_payments' => auth()->user()->can('delete_payments'),
                'view_orders' => auth()->user()->can('view_all_orders'), // Permiso para ver órdenes si es necesario en la vista de pagos
            ],
        ]);
    }

    /**
     * Muestra el formulario para crear un nuevo pago.
     */
    public function create()
    {
        return Inertia::render('Payments/Create', [
            // No se pasan órdenes aquí, se buscarán dinámicamente en el frontend
        ]);
    }

    /**
     * Almacena un nuevo pago en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'orders_id' => ['required', 'exists:orders,id'], // Asegura que la orden exista
            'payment_date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'currency' => ['required', 'string', 'max:3'], // Ej. USD, EUR
            'payment_method' => ['required', 'string', Rule::in(['cash', 'card', 'bank_transfer', 'other'])],
            'reference_number' => ['nullable', 'string', 'max:255'],
            'note' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', 'string', Rule::in(['pending', 'completed', 'failed', 'refunded'])],
        ]);

        DB::transaction(function () use ($request) {
            Payment::create([
                'orders_id' => $request->orders_id,
                'payment_date' => $request->payment_date,
                'amount' => $request->amount,
                'currency' => $request->currency,
                'payment_method' => $request->payment_method,
                'reference_number' => $request->reference_number,
                'note' => $request->note,
                'status' => $request->status,
            ]);
        });

        return redirect()->route('payments.index')->with('success', 'Pago creado exitosamente.');
    }

    /**
     * Muestra los detalles de un pago específico.
     */
    public function show(Payment $payment)
    {
        // Carga la orden asociada y sus relaciones (cliente, usuario)
        $payment->load('order.customer', 'order.user');

        return Inertia::render('Payments/Show', [
            'payment' => $payment,
            'can' => [
                'edit_payments' => auth()->user()->can('edit_payments'),
                'delete_payments' => auth()->user()->can('delete_payments'),
            ],
        ]);
    }

    /**
     * Muestra el formulario para editar un pago existente.
     */
    public function edit(Payment $payment)
    {
        // Carga la orden asociada para mostrar sus detalles en el formulario de edición
        $payment->load('order.customer', 'order.user');

        return Inertia::render('Payments/Edit', [
            'payment' => $payment,
        ]);
    }

    /**
     * Actualiza un pago existente en la base de datos.
     */
    public function update(Request $request, Payment $payment)
    {
        $request->validate([
            'payment_date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'currency' => ['required', 'string', 'max:3'],
            'payment_method' => ['required', 'string', Rule::in(['cash', 'card', 'bank_transfer', 'other'])],
            'reference_number' => ['nullable', 'string', 'max:255'],
            'note' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', 'string', Rule::in(['pending', 'completed', 'failed', 'refunded'])],
        ]);

        DB::transaction(function () use ($request, $payment) {
            $payment->update([
                'payment_date' => $request->payment_date,
                'amount' => $request->amount,
                'currency' => $request->currency,
                'payment_method' => $request->payment_method,
                'reference_number' => $request->reference_number,
                'note' => $request->note,
                'status' => $request->status,
            ]);
        });

        return redirect()->route('payments.show', $payment)->with('success', 'Pago actualizado exitosamente.');
    }

    /**
     * Elimina un pago de la base de datos.
     */
    public function destroy(Payment $payment)
    {
        DB::transaction(function () use ($payment) {
            $payment->delete();
        });

        return redirect()->route('payments.index')->with('success', 'Pago eliminado exitosamente.');
    }

    /**
     * Busca una orden por su ID para el frontend (usado en el formulario de creación/edición de pagos).
     */
// En PaymentController.php
    public function searchOrdersLive(Request $request)
    {
        $searchTerm = $request->input('query'); // Término de búsqueda

        // Intenta buscar por ID exacto si el término es numérico, de lo contrario, usa búsqueda parcial en otros campos.
        if (is_numeric($searchTerm)) {
            $orders = Order::with('customer', 'user')
                            ->where('id', $searchTerm) // Búsqueda por ID exacto
                            ->limit(1) // Solo queremos una coincidencia si es por ID
                            ->get();
        } else {
            // Si no es un ID numérico, buscar por nombre de equipo o nombre de cliente
            $orders = Order::with('customer', 'user')
                            ->where(function($q) use ($searchTerm) {
                                $q->where('name_equip', 'like', "%{$searchTerm}%")
                                  ->orWhereHas('customer', function($q2) use ($searchTerm) {
                                      $q2->where('fullname', 'like', "%{$searchTerm}%");
                                  });
                            })
                            ->limit(10) // Limitar resultados para autocompletado si hay muchos
                            ->get();
        }

        return response()->json($orders);
    }
}
