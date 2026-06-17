<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company; // Importa el modelo Company

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::create([
            'name' => 'SM Soluciones Electronicas C.A.',
            'description' => 'Sede principal de reparaciones electrónicas.',
            'phone' => '+58424-9342951',
            'email' => 'smsoluciones@gmail.com',
            'address' => 'Puerto Ordaz, Bolívar',
        ]);

    }
}
