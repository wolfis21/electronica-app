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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->dateTime('payment_date'); // Importante: DATETIME
            $table->decimal('amount', 10, 2); // Importante: DECIMAL para dinero
            $table->string('currency', 10); // Ej. 'USD', 'VES'
            $table->string('payment_method', 50); // Ej. 'Cash USD', 'Zelle', 'Transferencia VES'
            $table->string('reference_number', 100)->nullable();
            $table->text('note')->nullable(); // Cambiado a TEXT
            $table->string('status', 45); // Ej. 'aprobado', 'pendiente', 'rechazado'
            $table->foreignId('orders_id')->constrained('orders')->onDelete('cascade'); // FK a 'orders'
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
