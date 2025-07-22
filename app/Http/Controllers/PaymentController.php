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
        $payments = Payment::with('order.customer')->orderBy('payment_date', 'desc')->paginate(10);
        return Inertia::render('Payments/Index', [
            'payments' => $payments,
            'can' => [ 
                'create_payments' => auth()->user()->can('create_payments'),
                'edit_payments' => auth()->user()->can('edit_payments'),
                'delete_payments' => auth()->user()->can('delete_payments'),
                'view_orders' => auth()->user()->can('view_all_orders'),
            ],
        ]);
    }

    /**
     * Muestra el formulario para crear un nuevo pago.
     */
    public function create()
    {
        // 1. OBTENER TODAS LAS ÓRDENES ELEGIBLES PARA PAGO
        $eligibleOrders = Order::with(['customer', 'reviews', 'payments'])
            ->whereHas('reviews') // Solo órdenes con revisión
            ->where('payment_status', '!=', 'paid') // Y que no estén pagadas
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($order) {
                $totalDue = $order->reviews->first()->budget ?? 0;
                $totalPaid = $order->payments()->where('status', 'completed')->sum('amount');
                
                return [
                    'id' => $order->id,
                    'name_equip' => $order->name_equip,
                    'customer_name' => $order->customer->fullname,
                    'total_due' => (float) $totalDue,
                    'total_paid' => (float) $totalPaid,
                    'pending_balance' => (float) ($totalDue - $totalPaid),
                ];
            })
            // Filtramos para asegurarnos de que solo mostramos las que realmente tienen un saldo pendiente
            ->filter(fn($order) => $order['pending_balance'] > 0);

        // 2. PASAR LAS ÓRDENES A LA VISTA
        return Inertia::render('Payments/Create', [
            'eligibleOrders' => $eligibleOrders,
        ]);
    }

    /**
     * Almacena un nuevo pago en la base de datos.
     */
    public function store(Request $request)
    {
        // --- CAMBIO 1: Añadir validación para currency y status ---
        $request->validate([
            'orders_id' => ['required', 'exists:orders,id'],
            'payment_date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'currency' => ['required', 'string', 'max:10'],
            'payment_method' => ['required', 'string', Rule::in(['cash', 'card', 'bank_transfer', 'other'])],
            'reference_number' => ['nullable', 'string', 'max:255'],
            'note' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', 'string', Rule::in(['pending', 'completed', 'failed', 'refunded'])],
        ]);

        $order = Order::with(['reviews', 'payments'])->findOrFail($request->orders_id);
        
        if ($order->reviews->isEmpty()) {
            throw ValidationException::withMessages(['orders_id' => 'Esta orden no tiene una revisión y no se le pueden registrar pagos.']);
        }

        $totalDue = $order->reviews->first()->budget;
        $totalPaid = $order->payments->sum('amount');
        $pendingBalance = $totalDue - $totalPaid;

        if ($request->amount > $pendingBalance + 0.001) {
            throw ValidationException::withMessages(['amount' => 'El monto del pago no puede ser mayor que el saldo pendiente de $' . number_format($pendingBalance, 2)]);
        }

        DB::transaction(function () use ($request, $order) {
            // --- CAMBIO 2: Usar los valores del request en lugar de los hardcodeados ---
            $order->payments()->create([
                'payment_date' => $request->payment_date,
                'amount' => $request->amount,
                'currency' => $request->currency, // Usar la moneda del formulario
                'payment_method' => $request->payment_method,
                'reference_number' => $request->reference_number,
                'note' => $request->note,
                'status' => $request->status, // Usar el estado del formulario
            ]);

            // Solo actualiza el estado de la orden si el pago está 'completado'
            if ($request->status === 'completed') {
                $newTotalPaid = $order->fresh()->payments()->where('status', 'completed')->sum('amount');
                $newBalance = $order->reviews->first()->budget - $newTotalPaid;

                if ($newBalance <= 0) {
                    $order->payment_status = 'paid';
                } else {
                    $order->payment_status = 'partial';
                }
                $order->save();
            }
        });

        return redirect()->route('payments.index')->with('success', 'Pago registrado y estado de la orden actualizado.');
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
    public function searchOrdersForPayment(Request $request)
    {
        $searchTerm = $request->input('query');

        $orders = Order::with(['customer', 'reviews', 'payments'])
            ->where(function($q) use ($searchTerm) {
                $q->where('id', 'like', "%{$searchTerm}%")
                  ->orWhereHas('customer', function($q2) use ($searchTerm) {
                      $q2->where('fullname', 'like', "%{$searchTerm}%");
                  });
            })
            ->whereHas('reviews') // <-- SOLO ÓRDENES CON REVISIÓN
            ->where('payment_status', '!=', 'paid') // <-- SOLO ÓRDENES CON DEUDA
            ->limit(10)
            ->get()
            ->map(function ($order) {
                // Calculamos y añadimos los totales para que el frontend los reciba listos
                $totalDue = $order->reviews->first()->budget ?? 0;
                $totalPaid = $order->payments->sum('amount');
                
                return [
                    'id' => $order->id,
                    'name_equip' => $order->name_equip,
                    'customer_name' => $order->customer->fullname,
                    // --- CAMBIOS AQUÍ: Castear a float para asegurar que sean números en el JSON ---
                    'total_due' => (float) $totalDue,
                    'total_paid' => (float) $totalPaid,
                    'pending_balance' => (float) ($totalDue - $totalPaid),
                ];
            });

        return response()->json($orders);
    }
}
