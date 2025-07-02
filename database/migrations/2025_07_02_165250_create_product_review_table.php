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
        Schema::create('product_review', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('review_id')->constrained()->onDelete('cascade');
            $table->integer('quantity'); // Cantidad de este producto/servicio en la revisión
            $table->decimal('price_at_time_of_review', 10, 2); // Precio del producto/servicio en el momento de la revisión
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_review');
    }
};
