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
        Schema::create('reviews_products_services', function (Blueprint $table) {
            $table->foreignId('review_id')->constrained('reviews')->onDelete('cascade');
            $table->foreignId('product_service_id')->constrained('products_services')->onDelete('cascade');
            $table->primary(['review_id', 'product_service_id']); // Clave primaria compuesta
            // Puedes añadir más campos aquí, por ejemplo:
            // $table->integer('quantity')->default(1);
            // $table->decimal('price_at_time', 10, 2); (evaluar a posteriori)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews_products_services');
    }
};
