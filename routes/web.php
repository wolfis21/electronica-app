<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmployeeUserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\OrderDocumentController;
use App\Http\Controllers\PaymentController; 


Route::get('/', function () {
    $companyName = 'Electronica Tp-Link';
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'companyName' => $companyName,
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas para Roles y Permisos
    Route::resource('roles', RolePermissionController::class);

    // Rutas para Companies
    Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index'); // Para ver la info
    Route::get('/companies/{company}/edit', [CompanyController::class, 'edit'])->name('companies.edit'); // Para el formulario de edición
    Route::put('/companies/{company}', [CompanyController::class, 'update'])->name('companies.update'); // Para guardar cambios

    // Rutas para Empleados y Usuarios
    Route::resource('employees_users', EmployeeUserController::class);

    // Ruta específica para buscar cliente por DNI (API endpoint)
    Route::resource('customers', CustomerController::class);
    Route::get('/customers/search-by-dni/{dni}', [CustomerController::class, 'searchByDni'])->name('customers.searchByDni');

    // Rutas para Órdenes
    Route::resource('orders', OrderController::class);

    // Rutas para productos
    Route::resource('products', ProductController::class);

    // Rutas anidadas para Reviews
    Route::prefix('orders/{order}')->group(function () {
        Route::get('reviews/create', [ReviewController::class, 'create'])->name('orders.reviews.create');
        Route::post('reviews', [ReviewController::class, 'store'])->name('orders.reviews.store');
        Route::get('reviews/{review}', [ReviewController::class, 'show'])->name('reviews.show');
        Route::get('reviews/{review}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
        Route::put('reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
        Route::delete('reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    });

    // --- Rutas para Generar Documentos de Órdenes ---
    Route::get('/orders/{order}/documents/payment-receipt', [OrderDocumentController::class, 'generatePaymentReceipt'])
        ->name('orders.documents.payment');

    Route::get('/orders/{order}/documents/pickup-confirmation', [OrderDocumentController::class, 'generatePickupConfirmation'])
        ->name('orders.documents.pickup');

    Route::get('/orders/{order}/documents/delivery-order', [OrderDocumentController::class, 'generateDeliveryOrder'])
        ->name('orders.documents.delivery');
        // Rutas para pagos
        Route::get('/payments/search-orders-live', [PaymentController::class, 'searchOrdersLive'])->name('payments.searchOrdersLive');
        Route::resource('payments', PaymentController::class);
});



require __DIR__ . '/auth.php';
