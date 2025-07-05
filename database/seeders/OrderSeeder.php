<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Customer;
use App\Models\User; // Necesario para asignar un usuario responsable

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Asegúrate de que al menos un usuario exista para asignar como responsable
        $user = User::first();

        // Cliente 1 y su orden
        $customer1 = Customer::create([
            'fullname' => 'Ana García',
            'dni' => 'V-12345678',
            'phone' => '0412-1234567',
            'email' => 'ana.garcia@example.com',
            'address' => 'Calle Principal 123, Caracas',
        ]);

        Order::create([
            'name_equip' => 'Laptop HP Pavilion',
            'serial' => 'HPPV-2023-001',
            'description' => 'No enciende, posible falla de placa base.',
            'accessories' => 'Cargador, mochila',
            'extra_notes' => 'Cliente necesita el equipo con urgencia para trabajo.',
            'status' => 'En proceso',
            'customers_id' => $customer1->id,
            'users_id' => $user->id,
        ]);
        $this->command->info('Orden 1 y Cliente 1 creados.');

        // Cliente 2 y su orden
        $customer2 = Customer::create([
            'fullname' => 'Luis Fernández',
            'dni' => 'E-98765432',
            'phone' => '0424-9876543',
            'email' => 'luis.fernandez@example.com',
            'address' => 'Avenida Bolívar, Edif. Central, Apt. 5A, Valencia',
        ]);

        Order::create([
            'name_equip' => 'Smartphone Samsung Galaxy',
            'serial' => 'SMG-2023-002',
            'description' => 'Pantalla rota, no responde al tacto.',
            'accessories' => 'Sin accesorios',
            'extra_notes' => 'Presupuesto estimado de reparación de pantalla.',
            'status' => 'Pendiente',
            'customers_id' => $customer2->id,
            'users_id' => $user->id,
        ]);
        $this->command->info('Orden 2 y Cliente 2 creados.');

        // Cliente 3 y su orden
        $customer3 = Customer::create([
            'fullname' => 'María López',
            'dni' => 'V-11223344',
            'phone' => '0416-1122334',
            'email' => 'maria.lopez@example.com',
            'address' => 'Urbanización El Paraíso, Casa 45, Maracay',
        ]);

        Order::create([
            'name_equip' => 'Impresora Epson EcoTank',
            'serial' => 'EPTK-2023-003',
            'description' => 'No imprime, error de cartuchos.',
            'accessories' => 'Cable de poder',
            'extra_notes' => 'Revisar inyectores y sistema de tinta.',
            'status' => 'En proceso',
            'customers_id' => $customer3->id,
            'users_id' => $user->id,
        ]);
        $this->command->info('Orden 3 y Cliente 3 creados.');
    }
}
