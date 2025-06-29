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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('name_equip', 45);
            $table->string('serial', 45)->nullable();
            $table->text('description')->nullable(); // Cambiado a TEXT
            $table->text('accessories')->nullable(); // Cambiado a TEXT
            $table->text('extra_notes')->nullable(); // Cambiado a TEXT
            $table->string('status', 45); // Ej. 'pendiente', 'en_revision', 'finalizada'
            $table->foreignId('customers_id')->constrained('customers')->onDelete('restrict'); // FK a 'customers'
            $table->foreignId('users_id')->constrained('users')->onDelete('restrict'); // FK a 'users' (el usuario que gestiona)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
