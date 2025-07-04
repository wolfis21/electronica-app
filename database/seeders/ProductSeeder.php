<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product; // Importa el modelo Product

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Productos Físicos
        Product::create([
            'name' => 'Disco Duro SSD 512GB',
            'code' => 'SSD001',
            'description' => 'Unidad de estado sólido de alta velocidad para mejorar el rendimiento del equipo.',
            'price' => 40.00,
            'price_sale' => 55.00,
            'is_service' => false,
            'stock' => 50,
        ]);

        Product::create([
            'name' => 'Memoria RAM DDR4 8GB',
            'code' => 'RAM002',
            'description' => 'Módulo de memoria RAM de 8GB para laptops y desktops.',
            'price' => 25.00,
            'price_sale' => 35.00,
            'is_service' => false,
            'stock' => 100,
        ]);

        // Servicios
        Product::create([
            'name' => 'Diagnóstico y Presupuesto',
            'code' => 'SRV001',
            'description' => 'Revisión completa del equipo para identificar fallas y proponer soluciones.',
            'price' => null, // Los servicios pueden no tener un precio de compra
            'price_sale' => 10.00,
            'is_service' => true,
            'stock' => null, // Los servicios no tienen stock
        ]);

        Product::create([
            'name' => 'Formateo y Reinstalación de SO',
            'code' => 'SRV002',
            'description' => 'Formateo de disco duro y reinstalación del sistema operativo con drivers.',
            'price' => null,
            'price_sale' => 30.00,
            'is_service' => true,
            'stock' => null,
        ]);

        Product::create([
            'name' => 'Limpieza Interna de PC/Laptop',
            'code' => 'SRV003',
            'description' => 'Limpieza de componentes internos, cambio de pasta térmica y optimización de ventilación.',
            'price' => null,
            'price_sale' => 25.00,
            'is_service' => true,
            'stock' => null,
        ]);

        Product::create([
            'name' => 'Instalación de Software Básico',
            'code' => 'SRV004',
            'description' => 'Instalación de paquetes de oficina, navegadores y utilidades esenciales.',
            'price' => null,
            'price_sale' => 15.00,
            'is_service' => true,
            'stock' => null,
        ]);

        Product::create([
            'name' => 'Reparación de Placa Base (Nivel 1)',
            'code' => 'SRV005',
            'description' => 'Reparaciones menores en placa base, como sustitución de condensadores o puertos.',
            'price' => null,
            'price_sale' => 80.00,
            'is_service' => true,
            'stock' => null,
        ]);

        $this->command->info('¡Productos y servicios creados exitosamente!');
    }
}
