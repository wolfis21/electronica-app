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
        Schema::create('products_services', function (Blueprint $table) {
            $table->id();
            $table->string('name', 45);
            $table->decimal('price_internal', 10, 2)->nullable(); // Importante: DECIMAL para dinero
            $table->decimal('price_external', 10, 2)->nullable(); // Importante: DECIMAL para dinero
            $table->string('suppliers', 45)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products_services');
    }
};
