<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Company $company;
    protected Employee $employee;
    protected Role $role;

    protected function setUp(): void
    {
        parent::setUp();

        // Configurar empresa, empleado, rol y usuario para autenticación
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

        $this->user = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'employees_id' => $this->employee->id,
            'role_id' => $this->role->id,
        ]);
    }

    public function test_user_can_create_order(): void
    {
        $this->actingAs($this->user);

        $response = $this->post(route('orders.store'), [
            'customer_dni' => 'V-11111111',
            'customer_fullname' => 'Cliente Nuevo',
            'customer_phone' => '04141234567',
            'customer_address' => 'Calle Falsa 123',
            'customer_email' => 'cliente@test.com',
            'customer_found' => 'false',
            
            'name_equip' => 'Router TP-Link Archer C6',
            'serial' => 'SN12345678',
            'description' => 'No enciende',
            'accessories' => 'Cargador de corriente',
            'extra_notes' => 'Tiene raspaduras',
            'status' => 'Pendiente',
            'users_id' => $this->user->id,
        ]);

        $response->assertRedirect(route('orders.index'));
        $this->assertDatabaseHas('customers', ['dni' => 'V-11111111']);
        $this->assertDatabaseHas('orders', ['serial' => 'SN12345678']);
    }

    public function test_order_update_only_validated_fields(): void
    {
        $this->actingAs($this->user);

        $customer = Customer::create([
            'fullname' => 'Cliente Test',
            'dni' => 'V-22222222',
        ]);

        $order = Order::create([
            'name_equip' => 'Laptop HP',
            'serial' => 'SN98765',
            'status' => 'Pendiente',
            'customers_id' => $customer->id,
            'users_id' => $this->user->id,
        ]);

        // Intentar actualizar inyectando un campo no permitido
        $response = $this->put(route('orders.update', $order->id), [
            'customer_fullname' => 'Cliente Modificado',
            'name_equip' => 'Laptop HP ProBook',
            'serial' => 'SN98765-MOD',
            'status' => 'En proceso',
            'users_id' => $this->user->id,
            'non_existent_column' => 'hack_value', // Campo no fillable
        ]);

        $response->assertRedirect(route('orders.index'));
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'name_equip' => 'Laptop HP ProBook',
            'serial' => 'SN98765-MOD',
        ]);
        
        // Verificar que el cliente asociado también se actualizó
        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'fullname' => 'Cliente Modificado',
        ]);
    }

    public function test_order_pdf_generation_endpoints(): void
    {
        $this->actingAs($this->user);

        $customer = Customer::create([
            'fullname' => 'Cliente PDF',
            'dni' => 'V-33333333',
        ]);

        $order = Order::create([
            'name_equip' => 'Consola PS5',
            'serial' => 'SNPS5',
            'status' => 'En proceso',
            'customers_id' => $customer->id,
            'users_id' => $this->user->id,
        ]);

        // Crear una revisión asociada ya que los documentos de pago/retiro/delivery lo requieren
        $review = Review::create([
            'description_tec' => 'Limpieza y cambio de pasta térmica',
            'budget' => 50.00,
            'orders_id' => $order->id,
            'user_id' => $this->user->id,
        ]);

        // Asociar un servicio/producto de prueba
        $product = Product::create([
            'name' => 'Mantenimiento General',
            'price_sale' => 50.00,
            'is_service' => true,
        ]);

        $review->products()->attach($product->id, [
            'quantity' => 1,
            'price_at_time_of_review' => 50.00,
        ]);

        // 1. Endpoint: Imprimir orden general
        $response1 = $this->get(route('orders.pdf', $order->id));
        $response1->assertStatus(200);
        $response1->assertHeader('content-type', 'application/pdf');

        // 2. Endpoint: Recibo de pago
        $response2 = $this->get(route('orders.documents.payment', $order->id));
        $response2->assertStatus(200);
        $response2->assertHeader('content-type', 'application/pdf');

        // 3. Endpoint: Confirmación de retiro
        $response3 = $this->get(route('orders.documents.pickup', $order->id));
        $response3->assertStatus(200);
        $response3->assertHeader('content-type', 'application/pdf');

        // 4. Endpoint: Orden de entrega
        $response4 = $this->get(route('orders.documents.delivery', $order->id));
        $response4->assertStatus(200);
        $response4->assertHeader('content-type', 'application/pdf');
    }
}
