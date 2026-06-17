<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Company $company;
    protected Employee $employee;
    protected Role $role;

    protected function setUp(): void
    {
        parent::setUp();

        $this->company = Company::create([
            'name' => 'Test C.A.',
            'phone' => '123456',
            'email' => 'test@test.com',
            'address' => 'Puerto Ordaz',
        ]);

        $this->employee = Employee::create([
            'fullname' => 'Empleado Test',
            'dni' => 'V-87654321',
            'phone' => '123456',
            'address' => 'Puerto Ordaz',
            'companies_id' => $this->company->id,
        ]);

        $this->role = Role::create([
            'name' => 'Administrador',
            'description' => 'Administrador del sistema',
        ]);

        $createPerm = Permission::create([
            'name' => 'create_payments',
            'description' => 'Registrar pagos',
        ]);

        $viewPerm = Permission::create([
            'name' => 'view_payments',
            'description' => 'Ver pagos',
        ]);

        $this->role->permissions()->attach([$createPerm->id, $viewPerm->id]);

        $this->user = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'employees_id' => $this->employee->id,
            'role_id' => $this->role->id,
        ]);
    }

    public function test_user_can_register_payment(): void
    {
        $this->actingAs($this->user);

        $customer = Customer::create([
            'fullname' => 'Cliente Pago',
            'dni' => 'V-44444444',
        ]);

        $order = Order::create([
            'name_equip' => 'Router Archer C6',
            'serial' => 'SN123',
            'status' => 'En proceso',
            'customers_id' => $customer->id,
            'users_id' => $this->user->id,
            'payment_status' => 'pending',
        ]);

        // Crear revisión con presupuesto de $100
        $review = Review::create([
            'description_tec' => 'Cambio de tarjeta lógica',
            'budget' => 100.00,
            'orders_id' => $order->id,
            'user_id' => $this->user->id,
        ]);

        // Registrar un primer pago parcial de $40
        $response1 = $this->post(route('payments.store'), [
            'orders_id' => $order->id,
            'payment_date' => now()->toDateString(),
            'amount' => 40.00,
            'currency' => 'USD',
            'payment_method' => 'cash',
            'reference_number' => 'REF001',
            'note' => 'Pago de adelanto',
            'status' => 'completed',
        ]);

        $response1->assertRedirect(route('payments.index'));
        $this->assertDatabaseHas('payments', [
            'orders_id' => $order->id,
            'amount' => 40.00,
            'status' => 'completed',
        ]);

        // Estado de pago de la orden debe ser "partial"
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'payment_status' => 'partial',
        ]);

        // Registrar el pago restante de $60
        $response2 = $this->post(route('payments.store'), [
            'orders_id' => $order->id,
            'payment_date' => now()->toDateString(),
            'amount' => 60.00,
            'currency' => 'USD',
            'payment_method' => 'card',
            'reference_number' => 'REF002',
            'status' => 'completed',
        ]);

        $response2->assertRedirect(route('payments.index'));
        
        // Estado de pago de la orden debe ser "paid" ahora
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'payment_status' => 'paid',
        ]);
    }

    public function test_payment_validation_exception_behavior(): void
    {
        $this->actingAs($this->user);

        $customer = Customer::create([
            'fullname' => 'Cliente Pago Fallido',
            'dni' => 'V-55555555',
        ]);

        // Orden CREADA SIN REVISIÓN
        $order = Order::create([
            'name_equip' => 'Router Archer C6',
            'serial' => 'SN123',
            'status' => 'En proceso',
            'customers_id' => $customer->id,
            'users_id' => $this->user->id,
            'payment_status' => 'pending',
        ]);

        // Intentar pagar por la orden (debería fallar la validación porque no tiene revisión)
        $response = $this->post(route('payments.store'), [
            'orders_id' => $order->id,
            'payment_date' => now()->toDateString(),
            'amount' => 50.00,
            'currency' => 'USD',
            'payment_method' => 'cash',
            'status' => 'completed',
        ]);

        // Debería redirigir de vuelta con errores de validación en la sesión
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['orders_id']);
    }
}
