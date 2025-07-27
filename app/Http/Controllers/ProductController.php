<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:view_products')->only(['index', 'show']);
        $this->middleware('can:create_products')->only(['create', 'store']);
        $this->middleware('can:edit_products')->only(['edit', 'update']);
        $this->middleware('can:delete_products')->only('destroy');
    }

    /**
     * Display a listing of the resource (Products).
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Product::query();

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhere('code', 'like', '%' . $search . '%'); // Añadir búsqueda por código
        }

        $products = $query->paginate(10)->orderBy('id', 'desc')->withQueryString();

        return Inertia::render('Products/Index', [
            'products' => $products,
            'filters' => $request->only('search'),
            'can' => [
                'view_products' => auth()->user()->can('view_products'),
                'create_products' => auth()->user()->can('create_products'),
                'edit_products' => auth()->user()->can('edit_products'),
                'delete_products' => auth()->user()->can('delete_products'),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Products/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:products,name'],
            'code' => ['nullable', 'string', 'max:255'], // Nuevo campo, nullable
            'description' => ['nullable', 'string'],
            'price' => ['nullable', 'numeric', 'min:0'], // Ahora nullable
            'price_sale' => ['required', 'numeric', 'min:0'], // Nuevo campo, requerido
            'is_service' => ['required', 'boolean'],
            'stock' => ['nullable', 'integer', 'min:0', Rule::requiredIf(!$request->is_service)],
        ]);

        if ($validated['is_service']) {
            $validated['stock'] = null;
        }

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Producto/Servicio creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return Inertia::render('Products/Show', [
            'product' => $product,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return Inertia::render('Products/Edit', [
            'product' => $product,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('products')->ignore($product->id)],
            'code' => ['nullable', 'string', 'max:255'], // Nuevo campo, nullable
            'description' => ['nullable', 'string'],
            'price' => ['nullable', 'numeric', 'min:0'], // Ahora nullable
            'price_sale' => ['required', 'numeric', 'min:0'], // Nuevo campo, requerido
            'is_service' => ['required', 'boolean'],
            'stock' => ['nullable', 'integer', 'min:0', Rule::requiredIf(!$request->is_service)],
        ]);

        if ($validated['is_service']) {
            $validated['stock'] = null;
        }

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Producto/Servicio actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Producto/Servicio eliminado exitosamente.');
    }
}