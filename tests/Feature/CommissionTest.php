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

class CommissionTest extends TestCase
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
            'name' => 'Técnico', // Rol de técnico
            'description' => 'Técnico reparador',
        ]);

        $this->user = User::create([
            'name' => 'Tecnico Test',
            'email' => 'tecnico@test.com',
            'password' => bcrypt('password'),
            'employees_id' => $this->employee->id,
            'role_id' => $this->role->id,
        ]);
    }

    public function test_commission_calculation_based_on_config(): void
    {
        $this->actingAs($this->user);

        // Configurar tasa de comisión a 25% temporalmente para el test
        config(['app.commission_rate' => 0.25]);

        $customer = Customer::create([
            'fullname' => 'Cliente Comisión',
            'dni' => 'V-66666666',
        ]);

        $order = Order::create([
            'name_equip' => 'Router',
            'status' => 'En proceso',
            'customers_id' => $customer->id,
            'users_id' => $this->user->id,
        ]);

        // Crear una revisión técnica
        $review = Review::create([
            'description_tec' => 'Reparación de firmware',
            'budget' => 200.00,
            'orders_id' => $order->id,
            'user_id' => $this->user->id,
        ]);

        // Crear un producto de tipo SERVICIO (la comisión aplica sobre servicios)
        $service = Product::create([
            'name' => 'Servicio de Soldadura',
            'price_sale' => 100.00,
            'is_service' => true,
        ]);

        // Crear un producto físico (los productos físicos no generan comisión)
        $sparePart = Product::create([
            'name' => 'Capacitor 10uF',
            'price_sale' => 50.00,
            'is_service' => false,
            'stock' => 10,
        ]);

        // Asociar ambos a la revisión
        $review->products()->attach($service->id, [
            'quantity' => 1,
            'price_at_time_of_review' => 100.00,
        ]);

        $review->products()->attach($sparePart->id, [
            'quantity' => 2,
            'price_at_time_of_review' => 50.00,
        ]);

        $period = now()->day <= 15 ? 'first_half' : 'second_half';

        // Obtener detalles de comisiones del endpoint
        $response = $this->get(route('commissions.index', [
            'period' => $period,
        ]));

        $response->assertStatus(200);
        
        $pendingCommissions = $response->original->getData()['page']['props']['pendingCommissions'];
        
        $this->assertCount(1, $pendingCommissions);
        $this->assertEquals($this->user->id, $pendingCommissions[0]['user_id']);
        $this->assertEquals(25.00, $pendingCommissions[0]['total_commission']);
    }
}
