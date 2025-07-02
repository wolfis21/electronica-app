<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Nombre del producto o servicio
            $table->text('code')->nullable();//EAN o codigo unico de producto
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->nullable(); // Precio con 2 decimales
            $table->decimal('price_sale', 10, 2); // Precio con 2 decimales
            $table->boolean('is_service')->default(false); // true si es un servicio, false si es un producto
            $table->integer('stock')->nullable(); // Cantidad en inventario (nulo para servicios)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};