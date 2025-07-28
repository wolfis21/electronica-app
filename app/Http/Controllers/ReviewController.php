<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Review;
use App\Models\Product; // Importar el modelo Product
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB; // Para transacciones

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:view_reviews')->only(['show']); // Solo se puede ver una revisión individual
        $this->middleware('can:create_reviews')->only(['create', 'store']);
        $this->middleware('can:edit_reviews')->only(['edit', 'update']);
        $this->middleware('can:delete_reviews')->only('destroy');
        $this->middleware('can:view_orders'); // Necesario para acceder a la orden padre
    }

    // No hay index global de revisiones, se acceden desde la orden.

    /**
     * Show the form for creating a new review for a specific order.
     */
    public function create(Order $order)
    {
        if ($order->reviews()->exists()) {
            return redirect()->route('orders.show', $order)->with('error', 'Esta orden ya tiene una revisión creada.');
        }

        // Obtener solo productos y servicios activos o disponibles para seleccionar
        $products = Product::select('id', 'name', 'code', 'price_sale', 'is_service', 'stock')->orderBy('name')->get();

        return Inertia::render('Reviews/Create', [
            'order' => $order,
            'products' => $products,
        ]);
    }

    /**
     * Store a newly created review in storage.
     */
    public function store(Request $request, Order $order)
    {
        if ($order->reviews()->exists()) {
            return redirect()->route('orders.show', $order)->with('error', 'Esta orden ya tiene una revisión.');
        }

        $request->validate([
            'description_tec' => ['required', 'string', 'max:5000'],
            // 'budget' se calculará en el backend
            'selected_products' => ['nullable', 'array'], // Array de objetos {id, quantity, price_sale}
            'selected_products.*.id' => ['required', 'exists:products,id'],
            'selected_products.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        DB::transaction(function () use ($request, $order) {
            $totalBudget = 0;
            $selectedProductsData = [];
            $productsToUpdateStock = [];

            if ($request->has('selected_products') && is_array($request->selected_products)) {
                foreach ($request->selected_products as $selectedProduct) {
                    $product = Product::find($selectedProduct['id']);
                    if (!$product) {
                        abort(404, 'Producto/Servicio no encontrado.');
                    }

                    $quantity = (int) $selectedProduct['quantity'];
                    $priceAtTimeOfReview = $product->price_sale; // Usar el price_sale actual del producto

                    $totalBudget += ($priceAtTimeOfReview * $quantity);

                    // Preparar datos para la tabla pivote
                    $selectedProductsData[$product->id] = [
                        'quantity' => $quantity,
                        'price_at_time_of_review' => $priceAtTimeOfReview,
                        'created_at' => now(), // Para timestamps en la tabla pivote
                        'updated_at' => now(), // Para timestamps en la tabla pivote
                    ];

                    // Si es un producto físico, preparar para actualizar el stock
                    if (!$product->is_service) {
                        $productsToUpdateStock[] = [
                            'product' => $product,
                            'quantity' => $quantity,
                        ];
                    }
                }
            }

            // Crear la revisión
            $review = $order->reviews()->create([
                'description_tec' => $request->description_tec,
                'budget' => $totalBudget,
            ]);

            // Adjuntar productos/servicios a la revisión
            if (!empty($selectedProductsData)) {
                $review->products()->attach($selectedProductsData);
            }

            // Actualizar el stock de productos
            foreach ($productsToUpdateStock as $item) {
                $product = $item['product'];
                $quantity = $item['quantity'];
                // Asegúrate de que el stock no sea negativo
                if ($product->stock !== null && $product->stock >= $quantity) {
                    $product->decrement('stock', $quantity);
                } else if ($product->stock !== null && $product->stock < $quantity) {
                    // Opcional: manejar si no hay suficiente stock.
                    // Por ahora, simplemente decrementa hasta 0 o lanza un error.
                    // Para este ejemplo, solo avisamos y seguimos, pero en producción, quizás quieras abortar la transacción.
                    session()->flash('warning', "Advertencia: El stock de '{$product->name}' es insuficiente ({$product->stock} disponibles, {$quantity} requeridos).");
                    $product->stock = 0; // O decrementa hasta 0 si no hay suficiente
                    $product->save();
                }
            }
        });

        return redirect()->route('orders.show', $order)->with('success', 'Revisión creada exitosamente y productos/servicios asociados.');
    }

    /**
     * Display the specified review.
     */
    public function show(Order $order, Review $review)
    {
        if ($review->orders_id !== $order->id) {
            abort(404); // La revisión no pertenece a esta orden
        }

        // Cargar los productos/servicios asociados con la revisión
        $review->load('products');

        return Inertia::render('Reviews/Show', [
            'order' => $order,
            'review' => $review,
            'can' => [ // Add this 'can' array
                'edit_reviews' => auth()->user()->can('edit_reviews'),
                'delete_reviews' => auth()->user()->can('delete_reviews'),
            ],
        ]);
    }

    /**
     * Show the form for editing the specified review.
     */
    public function edit(Order $order, Review $review)
    {
        if ($review->orders_id !== $order->id) {
            abort(404);
        }

        // Cargar los productos/servicios asociados
        $review->load('products');
        $products = Product::select('id', 'name', 'code', 'price_sale', 'is_service', 'stock')->orderBy('name')->get();

        return Inertia::render('Reviews/Edit', [
            'order' => $order,
            'review' => $review,
            'products' => $products,
        ]);
    }

    /**
     * Update the specified review in storage.
     */
    public function update(Request $request, Order $order, Review $review)
    {
        if ($review->orders_id !== $order->id) {
            abort(404);
        }

        $request->validate([
            'description_tec' => ['required', 'string', 'max:5000'],
            'selected_products' => ['nullable', 'array'],
            'selected_products.*.id' => ['required', 'exists:products,id'],
            'selected_products.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        DB::transaction(function () use ($request, $order, $review) {
            $totalBudget = 0;
            $selectedProductsData = [];
            $productsToUpdateStock = [];

            // Paso 1: Revertir stock de los productos previamente asociados
            $oldAttachedProducts = $review->products()->get();
            foreach ($oldAttachedProducts as $oldProduct) {
                if (!$oldProduct->is_service && $oldProduct->pivot->quantity > 0) {
                    Product::find($oldProduct->id)->increment('stock', $oldProduct->pivot->quantity);
                }
            }

            // Paso 2: Procesar los nuevos productos seleccionados
            if ($request->has('selected_products') && is_array($request->selected_products)) {
                foreach ($request->selected_products as $selectedProduct) {
                    $product = Product::find($selectedProduct['id']);
                    if (!$product) {
                        abort(404, 'Producto/Servicio no encontrado.');
                    }

                    $quantity = (int) $selectedProduct['quantity'];
                    $priceAtTimeOfReview = $product->price_sale;

                    $totalBudget += ($priceAtTimeOfReview * $quantity);

                    $selectedProductsData[$product->id] = [
                        'quantity' => $quantity,
                        'price_at_time_of_review' => $priceAtTimeOfReview,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    if (!$product->is_service) {
                        $productsToUpdateStock[] = [
                            'product' => $product,
                            'quantity' => $quantity,
                        ];
                    }
                }
            }

            // Actualizar la revisión
            $review->update([
                'description_tec' => $request->description_tec,
                'budget' => $totalBudget,
            ]);

            // Sincronizar los productos/servicios (elimina los viejos y adjunta los nuevos)
            if (!empty($selectedProductsData)) {
                $review->products()->sync($selectedProductsData);
            } else {
                $review->products()->detach(); // Si no hay productos seleccionados, desvincula todos
            }

            // Actualizar el stock de los productos físicos (decrementando por las nuevas cantidades)
            foreach ($productsToUpdateStock as $item) {
                $product = $item['product'];
                $quantity = $item['quantity'];
                if ($product->stock !== null && $product->stock >= $quantity) {
                    $product->decrement('stock', $quantity);
                } else if ($product->stock !== null && $product->stock < $quantity) {
                    session()->flash('warning', "Advertencia: El stock de '{$product->name}' es insuficiente ({$product->stock} disponibles, {$quantity} requeridos).");
                    $product->stock = 0;
                    $product->save();
                }
            }
        });

        return redirect()->route('reviews.show', [$order, $review])->with('success', 'Revisión actualizada exitosamente.');
    }

    /**
     * Remove the specified review from storage.
     */
    public function destroy(Order $order, Review $review)
    {
        if ($review->orders_id !== $order->id) {
            abort(404);
        }

        DB::transaction(function () use ($review) {
            // Revertir stock antes de eliminar la revisión
            $attachedProducts = $review->products()->get();
            foreach ($attachedProducts as $product) {
                if (!$product->is_service && $product->pivot->quantity > 0) {
                    Product::find($product->id)->increment('stock', $product->pivot->quantity);
                }
            }
            $review->delete();
        });


        return redirect()->route('orders.show', $order)->with('success', 'Revisión eliminada exitosamente.');
    }
}